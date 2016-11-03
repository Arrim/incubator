<?php
namespace Phalcon\Validation\Validator\Mongo;

use Phalcon\Db\Adapter\MongoDB\Database;
use Phalcon\Di;
use Phalcon\DiInterface;
use Phalcon\Mvc\Model\ValidationFailed;
use Phalcon\Mvc\MongoCollection;
use Phalcon\Validation;
use Phalcon\Validation\Validator;
use Phalcon\Validation\Message;
use Phalcon\Validation\Exception;

class Uniqueness extends Validator
{
    /**
     * Mongo Collection
     * @var MongoCollection
     */
    private $collection;

    /**
     * Class constructor.
     *
     * @param  array $options
     * @param  Database  $db
     * @throws Exception
     */
    public function __construct(array $options = [], MongoCollection $collection = null)
    {
        parent::__construct($options);

        if (!$collection) {
          throw new Exception('Not collection');
        }

        if (!$collection instanceof MongoCollection) {
            throw new Exception('Validator Uniqueness require collection');
        }

        $this->collection = $collection;
    }

    public function validate(Validation $validator, $attribute)
    {
        $count = $this->collection->count(array(
            [
                $attribute => $validator->getValue($attribute)
            ]
        ));
        $field = $this->getOption('field');
        if ($count > 0) {
            $message = $this->getOption('message');
            $validator->appendMessage(new Message($message, $field));
            return false;
        }
        return true;
    }
}