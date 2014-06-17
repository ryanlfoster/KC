<?php



/**
 * Thrown when the Sweet Tooth SDK isn't able to proceed as far as hitting the API
 */
class SweetToothSdkException extends Exception
{
    const CREDENTIALS_NOT_SPECIFIED = 100;

    /**
     * To make debugging easier.
     *
     * @return string The string representation of the error
     */
    public function __toString()
    {
        $str = 'Exception: ';
        if ($this->code != 0) {
            $str .= $this->code . ': ';
        }
        return $str . $this->message;
    }
}
