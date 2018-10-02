<?php

/*
 * (c) Lukasz D. Tulikowski <lukasz.tulikowski@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Exception;

use App\Exception\Enum\ApiErrorEnumType;
use Exception;

class ApiException extends Exception
{
    public const DEFAULT_MESSAGE = 'General error.';

    /**
     * @var string
     */
    protected $type;

    /**
     * @var array
     */
    protected $data;

    /**
     * {@inheritdoc}
     *
     * @param string $message
     * @param array $data
     * @param Exception $exception
     */
    public function __construct(string $message = '', array $data = [], Exception $exception = null)
    {
        parent::__construct($message, 0, $exception);

        $this->type = ApiErrorEnumType::GENERAL_ERROR;
        $this->data = $data;
    }

    /**
     * Never call this for method for ApiException. Please always provide messages for your
     * general errors (i.e. call the constructor).
     *
     * @param array $data
     *
     * @return static
     */
    public static function createWithData(array $data = [])
    {
        return new static(static::DEFAULT_MESSAGE, $data);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}
