<?php 

    /**
     * ErrorTrait
     */

    namespace Embryo\Validation\Traits;

    trait ErrorTrait
    {
        /**
         * @var array $messages
         */
        protected $messages = [
            'en' => [
                'name'     => 'The field %s not exists.',
                'type'     => 'The field %s must by a %s.',
                'required' => 'The field %s is required.',
                'maxSize'  => 'The field %s may not be greater than %s.',
                'length'   => 'The field %s may not be less than %s and greater than %s.',
                'equal'    => 'The field %s must be %s.',
                'accept'   => 'The field %s must be a %s.'
            ],
            'it' => [
                'name'     => 'Il campo %s non esiste.',
                'type'     => 'Il campo %s deve essere un %s.',
                'required' => 'Il campo %s è obbligatorio.',
                'maxSize'  => 'Il campo %s non può essere superiore a %s.',
                'length'   => 'Il campo %s non può essere inferiore a %s e superiore a %s.',
                'equal'    => 'Il campo %s deve essere uguale a %s.',
                'accept'   => 'Il campo %s deve essere un %s.'
            ]
        ];

        /**
         * @var array $errors
         */
        protected $errors = [];

        /**
         * @var string $lang
         */
        protected $lang;

        protected function error(string $type, array $vars = [])
        {
            $this->errors[$this->name][] = vsprintf($this->messages[$this->lang][$type], $vars);
        }
    }