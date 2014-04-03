<?php

namespace Rimote\Updater\Updater\Exception;

use Exception;

class UpdaterException extends Exception
{
    /**
     * Constructor. Takes a message string, optionally formatted printf-style
     * with any extra arguments. If the last argument is an Exception, it will
     * be used as the previous exception in the exception chain.
     */
    public function __construct($message = "") 
    {
        self::_clear_buffered();
        
        // Get the rest of the arguments (sans $message)
        $args = func_get_args();
        array_shift($args);
        
        // If the last argument is an Exception, use it as the previous
        // exception in the exception chain
        $previous = null;
        if (count($args) > 0 and is_a($args[count($args)-1], 'Exception')) {
            $previous = array_pop($args);
        }
        
        // Format the message if extra arguments were given
        //TODO: allow localization, perhaps some security hooks
        if (count($args) > 0) {
            $message = vsprintf($message, $args);
        }
        
        parent::__construct($message, 0, $previous);
    }
    
    /**
     * Helper method. Clear any buffered output.
     */
    public static function _clear_buffered() 
    {
        while (ob_get_level() > 0) {
            ob_end_clean();
        }
    }
    
    public function render() 
    {
        $output = '';
        
        if(defined('USER_FRIENDLY_ERRORS_ONLY')) {
            $output .= $this->_render_production();
        } 
        else {
            $output .= $this->_render_debug();
        }
        
        return $output;
    }
    
    /**
     * Get all messages, including previous exceptions
     */
    public function getAllMessages($options = array())
    {
        $error_msg = '';
        
        try {
            $e = $this;
            
            do {
                $exceptions[] = $e;
            } while ($e = $e->getPrevious());
        } 
        // An exception was thrown while trying to handle the exception...
        catch (Exception $e) {
            if(!defined('HIDE_ERRORS')) 
            {
                self::_clear_buffered();
                echo $exception->getMessage();
            
                $error_msg .= "<br><b>Additionally, an error occurred while"
                        . " handling the exception:</b><br>\n";
                $error_msg .= $e->getMessage();
            } else {
                exit("FATAL ERROR. Please reload page in a few minutes.");
            }
        }
        
        // Generate our error message
        $datetime = date('Y-m-d H:i:s');
        
        foreach ($exceptions as $exception) {
            $error_msg .= "[$datetime] " . get_class($exception) . ": " . $exception->getMessage() . "\n\n";
            
            if (isset($options['stack_traces']) && $options['stack_traces'] === true) {
               $error_msg .= $exception->getTraceAsString() . "\n";
            }
        }
        
        return $error_msg;
    }
    
    /**
     * Exception handler.
     */
    public static function handleException($exception)
    {
        $error_msg = $exception->getAllMessages(array('stack_traces' => true));
        
        // Log exception
        if(defined('ERROR_LOG_FILE') && file_exists(ERROR_LOG_FILE) && 
            is_writeable(ERROR_LOG_FILE)) {
            $error_log = file_get_contents(ERROR_LOG_FILE);
            $error_log = $error_msg . $error_log;
            file_put_contents(ERROR_LOG_FILE, $error_log);
        }
        
        // Now finally, show error to the user
        echo $error_msg;
        exit;
    }
}