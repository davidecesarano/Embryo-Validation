<?php 

    /**
     * ErrorTrait
     * 
     * @author Davide Cesarano <davide.cesarano@unipegaso.it>
     * @link   https://github.com/davidecesarano/embryo-validation  
     */

    namespace Embryo\Validation\Traits;

    trait ErrorTrait
    {
        /**
         * @var array $messages
         */
        protected $messages = [
            'en' => [
                'name'     => 'The %s field not exists.',
                'type'     => 'The %s field must by a %s.',
                'required' => 'The %s field is required.',
                'pattern'  => 'The %s field must match regular expression %s.',
                'equal'    => 'The %s field must be %s.',
                'match'    => 'The %s field must match to one of those value: %s.',
                'length'   => 'The %s field may not be less than %s and greater than %s.',
                'maxSize'  => 'The %s field may not be greater than %s.',
                'accept'   => 'The %s field must be a %s.',
                'slug'     => 'The %s field must be a slug.'
            ],
            'it' => [
                'name'     => 'Il campo %s non esiste.',
                'type'     => 'Il campo %s deve essere un %s.',
                'required' => 'Il campo %s è obbligatorio.',
                'pattern'  => 'Il campo %s deve corrispondere all\'espressione regolare %s.',
                'equal'    => 'Il campo %s deve essere uguale a %s.',
                'match'    => 'Il campo %s deve corrispondere a uno di questi valori: %s.',
                'length'   => 'Il campo %s non può essere inferiore a %s e superiore a %s.',
                'maxSize'  => 'Il campo %s non può essere superiore a %s.',
                'accept'   => 'Il campo %s deve essere un %s.',
                'slug'     => 'Il campo %s deve essere uno slug.'
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

        /**
         * Error.
         *
         * @param string $type
         * @param array $vars
         * @return void
         */
        protected function error(string $type, array $vars = [])
        {
            $this->errors[$this->name][] = vsprintf($this->messages[$this->lang][$type], $vars);
        }
    }