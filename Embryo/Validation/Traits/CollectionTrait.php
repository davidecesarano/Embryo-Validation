<?php 

    /**
     * CollectionTrait
     */

    namespace Embryo\Validation\Traits;

    trait CollectionTrait
    {
        protected function has(string $name)
        {
            return (isset($this->body[$name]) || isset($this->file[$name])) ? true : false;
        }

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