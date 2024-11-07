<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */
namespace Isolated\Twilio\TwiML\Voice;

use Isolated\Twilio\TwiML\TwiML;
class Record extends TwiML
{
    /**
     * Record constructor.
     *
     * @param array $attributes Optional attributes
     */
    public function __construct($attributes = [])
    {
        parent::__construct('Record', null, $attributes);
    }
    /**
     * Add Action attribute.
     *
     * @param string $action Action URL
     */
    public function setAction($action) : self
    {
        return $this->setAttribute('action', $action);
    }
    /**
     * Add Method attribute.
     *
     * @param string $method Action URL method
     */
    public function setMethod($method) : self
    {
        return $this->setAttribute('method', $method);
    }
    /**
     * Add Timeout attribute.
     *
     * @param int $timeout Timeout to begin recording
     */
    public function setTimeout($timeout) : self
    {
        return $this->setAttribute('timeout', $timeout);
    }
    /**
     * Add FinishOnKey attribute.
     *
     * @param string $finishOnKey Finish recording on key
     */
    public function setFinishOnKey($finishOnKey) : self
    {
        return $this->setAttribute('finishOnKey', $finishOnKey);
    }
    /**
     * Add MaxLength attribute.
     *
     * @param int $maxLength Max time to record in seconds
     */
    public function setMaxLength($maxLength) : self
    {
        return $this->setAttribute('maxLength', $maxLength);
    }
    /**
     * Add PlayBeep attribute.
     *
     * @param bool $playBeep Play beep
     */
    public function setPlayBeep($playBeep) : self
    {
        return $this->setAttribute('playBeep', $playBeep);
    }
    /**
     * Add Trim attribute.
     *
     * @param string $trim Trim the recording
     */
    public function setTrim($trim) : self
    {
        return $this->setAttribute('trim', $trim);
    }
    /**
     * Add RecordingStatusCallback attribute.
     *
     * @param string $recordingStatusCallback Status callback URL
     */
    public function setRecordingStatusCallback($recordingStatusCallback) : self
    {
        return $this->setAttribute('recordingStatusCallback', $recordingStatusCallback);
    }
    /**
     * Add RecordingStatusCallbackMethod attribute.
     *
     * @param string $recordingStatusCallbackMethod Status callback URL method
     */
    public function setRecordingStatusCallbackMethod($recordingStatusCallbackMethod) : self
    {
        return $this->setAttribute('recordingStatusCallbackMethod', $recordingStatusCallbackMethod);
    }
    /**
     * Add RecordingStatusCallbackEvent attribute.
     *
     * @param string[] $recordingStatusCallbackEvent Recording status callback
     *                                               events
     */
    public function setRecordingStatusCallbackEvent($recordingStatusCallbackEvent) : self
    {
        return $this->setAttribute('recordingStatusCallbackEvent', $recordingStatusCallbackEvent);
    }
    /**
     * Add Transcribe attribute.
     *
     * @param bool $transcribe Transcribe the recording
     */
    public function setTranscribe($transcribe) : self
    {
        return $this->setAttribute('transcribe', $transcribe);
    }
    /**
     * Add TranscribeCallback attribute.
     *
     * @param string $transcribeCallback Transcribe callback URL
     */
    public function setTranscribeCallback($transcribeCallback) : self
    {
        return $this->setAttribute('transcribeCallback', $transcribeCallback);
    }
}
