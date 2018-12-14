<?php 

    /**
     * CollectionTrait
     * 
     * @author Davide Cesarano <davide.cesarano@unipegaso.it>
     * @link   https://github.com/davidecesarano/embryo-validation  
     */

    namespace Embryo\Validation\Traits;

    trait CollectionTrait
    {
        /**
         * Return true if field exists in body or 
         * uploaded file.
         *
         * @param string $name
         * @return bool
         */
        protected function has(string $name): bool
        {
            return (isset($this->body[$name]) || isset($this->file[$name])) ? true : false;
        }

        /**
         * Return field value from body or
         * uploaded file.
         *
         * @param string $name
         * @return mixed
         */
        protected function get(string $name)
        {
            if (isset($this->body[$name])) {
                return $this->body[$name];
            }

            if (isset($this->file[$name])) {
                return $this->file[$name];
            }

            return;
        }
    }