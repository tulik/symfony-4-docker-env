<?php

/*
 * (c) Lukasz D. Tulikowski <lukasz.tulikowski@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Exception;

use Exception;

class FormInvalidException extends ApiException
{
    public const DEFAULT_MESSAGE = "Submitted form didn't pass validation.";

    /**
     * @param string $message
     * @param array $data This must NOT be an empty array. See the usage of this exception to see
     *                    how to construct it correctly with Symfony form errors
     * @param Exception|null $exception
     */
    public function __construct(string $message = '', array $data = [], Exception $exception = null)
    {
        parent::__construct($message, $data, $exception);
    }
}
