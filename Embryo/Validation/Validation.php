<?php 

    /**
     * Validation
     */

    namespace Embryo\Validation;

    use Psr\Http\Message\{ServerRequestInterface, UploadedFileInterface};
    use Embryo\Validation\Exceptions\{InvalidFieldException, InvalidFieldTypeException};
    use Embryo\Validation\Traits\{CollectionTrait, ErrorTrait, FilterTrait};

    class Validation 
    {
        use CollectionTrait;
        use ErrorTrait;
        use FilterTrait;

        /**
         * @var array $body
         */
        private $body = [];
        
        /**
         * @var array $file
         */
        private $file = [];

        /**
         * @var string $name
         */
        private $name;

        /**
         * @var mixed $value
         */
        private $value = null;

        /**
         * @var array $data
         */
        private $data;

        /**
         * Set parsed body, uploaded files and
         * default language for error messages.
         *
         * @param ServerRequestInterface $request
         */
        public function __construct(ServerRequestInterface $request, string $lang = 'en')
        {
            $this->body = $request->getParsedBody();
            $this->file = $request->getUploadedFiles();
            $this->lang = $lang;
        }

        /**
         * Set field name and check if it exists.
         *
         * @param string $name
         * @return self
         */
        public function name(string $name): self
        {
            $this->name = $name;
            if (!$this->has($name)) {
                $this->error('name', [$name]);
            }
            return $this;
        }

        /**
         * Check if field value respects the type
         * and set data array.
         *
         * @param string $name
         * @return self
         * @throws InvalidArgumentException
         */
        public function type(string $type): self
        {
            if (!method_exists($this, $type)) {
                throw new \InvalidFieldTypeException("The field type must be email, file, array, datetime, number, int, float, url, boolean or any");
            }

            $this->value = $this->get($this->name);
            if (!$this->{$type}($this->value)) {
                $this->error('type', [$this->name, $type]);
            }

            $this->data[$this->name] = $this->sanitize($type, $this->value);
            return $this;
        }

        /**
         * Set field required.
         *
         * @return self
         */
        public function required(): self
        {   
            $required = false;

            // array
            if (is_array($this->value) && empty($this->value)) {
                $required = true;
            }

            // file[]
            if (is_array($this->value)) {
                foreach ($this->value as $file) {
                    if ($file instanceof UploadedFileInterface && $file->getError() === 4) {
                        $required = true;
                    }
                }
            }

            // file
            if (($this->value instanceof UploadedFileInterface) && ($this->value->getError() === 4)) {
                $required = true;
            }

            // any
            if ($this->value === '') {
                $required = true;
            }

            if ($required) {
                $this->error('required', [$this->name]);
            }
            return $this;
        }

        /**
         * Set field length.
         *
         * @param int $min
         * @param int $max
         * @return self
         */
        public function length(int $min, int $max): self
        {
            $length = false;
            if (is_string($this->value)) {
                if (strlen($this->value) < $min || strlen($this->value) > $max) {
                    $length = true;
                    
                }
            } else {
                if ($this->value < $min || $this->value > $max) {
                    $length = true;
                }
            }

            if ($length) {
                $this->error('length', [$this->name, $min, $max]);
            }
            return $this;
        }

        /**
         * Set default value.
         *
         * @param mixed $value
         * @return self
         */
        public function equal($value): self
        {
            if ($this->value != $value){
                $this->error('equal', [$this->name, $value]);
            }
            return $this;
        }

        /**
         * Set max size of the file.
         *
         * @param int $size
         * @return self
         */
        public function maxSize(int $size): self
        {
            $maxSize = false;
            if (!is_array($this->value) && !$this->value instanceof UploadedFileInterface) {
                throw new InvalidFieldException('maxSize method can be used only for file');
            }

            // file[]
            if (is_array($this->value)) {
                foreach ($this->value as $file) {
                    if ($file instanceof UploadedFileInterface && $file->getError() !== 4) {
                        if ($file->getSize() > $size) {
                            $maxSize = true;
                        }
                    }
                }
            }

            // file
            if (($this->value instanceof UploadedFileInterface) && ($this->value->getError() !== 4) && ($this->value->getSize() > $size)) {
                $maxSize = true;
            }

            if ($maxSize) {

                $base      = log($size, 1024);
                $suffixes  = ['', 'Kb', 'Mb', 'Gb', 'Tb'];   
                $megabytes = round(pow(1024, $base - floor($base)), 2) .' '. $suffixes[floor($base)];

                $this->error('maxSize', [$this->name, $megabytes]);

            }
            return $this;
        }

        public function accept(string $ext)
        {
            $accept = false;
            if (!is_array($this->value) && !$this->value instanceof UploadedFileInterface) {
                throw new InvalidFieldException('accept method can be used only for file');
            }

            // file[]
            if (is_array($this->value)) {
                foreach ($this->value as $file) {
                    if ($file instanceof UploadedFileInterface && $file->getError() !== 4) {
                        if ($file->getClientMediaType() !== $ext) {
                            $accept = true;
                        }
                    }
                }
            }

            // file
            if (($this->value instanceof UploadedFileInterface) && ($this->value->getError() !== 4) && ($this->value->getClientMediaType() !== $ext)) {
                $accept = true;
            }

            if ($accept) {
                $this->error('accept', [$this->name, $ext]);
            }
            return $this;
        }

        /**
         * Validation result.
         * 
         * Return an array with status (200 or 400), sanitized data,
         * errors and error list.
         *
         * @return array
         */
        public function result(): array
        {
            $status = (empty($this->errors)) ? 200 : 400;
            
            $errorList = [];
            foreach ($this->errors as $error) {
                foreach ($error as $message) {
                    $errorList[] = $message;
                }
            }

            return [
                'status'    => $status,
                'data'      => $this->data,
                'errors'    => $this->errors,
                'errorList' => $errorList
            ];
        }

        /**
         * Return true if validation is success.
         * 
         * @return bool
         */
        public function isSuccess(): bool
        {
            return empty($this->errors);
        }

        /**
         * Return data.
         *
         * @return array
         */
        public function getData(): array 
        {
            return $this->data;
        }

        /**
         * Return errors.
         *
         * @return array
         */
        public function getErrors(): array 
        {
            return $this->errors;
        }

        /**
         * Return error list.
         *
         * @return array
         */
        public function getErrorList(): array 
        {
            $errorList = [];
            foreach ($this->errors as $error) {
                foreach ($error as $message) {
                    $errorList[] = $message;
                }
            }
            return $errorList;
        }
    }