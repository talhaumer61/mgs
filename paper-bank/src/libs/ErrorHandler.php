<?php

/**
 * Class ErrorHandler
 * Handles the Error thrown by the application accordingly
 */

class ErrorHandler
{
    public function __construct(Exception $error)
    {

        /**
         * If the Error is By ApiError Class then send a json response.
         */
        if (get_class($error) == 'ApiError')
            echo json_encode($error->getError());

        /**
         * If the Error is AppError the set the flash error key in the Session
         */
        if (get_class($error) == "AppError") {
            echo $error->getRedirectRoute();
            Utils::setFlash($error->getError());
            return Utils::redirect($error->getRedirectRoute());
        }

        if ($error->getCode() == 23000){
            echo "Catch me if you can?";
            Utils::redirect(URLROOT, [
                'type' => 'error',
                'message' => "You can't perform this action because it will destroy the database"
            ]);
        }


    }
}
