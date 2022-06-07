<?php

namespace tci\interfaces;

/**
 * Callback interface
 */
interface CallbackInterface
{
    /**
     * Successful payment
     */
    const SUCCESS_STATUS = 'success';

    /**
     * Rejected payment
     */
    const DECLINE_STATUS = 'decline';

    /**
     * Awaiting a request with the result of a 3-D Secure Verification
     */
    const AW_3DS_STATUS = 'awaiting 3ds result';

    /**
     * Awaiting customer return after redirecting the customer to an external provider system
     */
    const AW_RED_STATUS = 'awaiting redirect result';

    /**
     * Awaiting customer actions, if the customer may perform additional attempts to make a payment
     */
    const AW_CUS_STATUS = 'awaiting customer';

    /**
     * Awaiting additional parameters
     */
    const AW_CLA_STATUS = 'awaiting clarification';

    /**
     * Awaiting request for withdrawal of funds (capture) or cancellation of payment (cancel) from your project
     */
    const AW_CAP_STATUS = 'awaiting capture';

    /**
     * Holding of funds (produced on authorization request) is cancelled
     */
    const CANCELLED_STATUS = 'cancelled';

    /**
     * Successfully completed the full refund after a successful payment
     */
    const REFUNDED_STATUS = 'refunded';

    /**
     * Completed partial refund after a successful payment
     */
    const PART_REFUNDED_STATUS = 'partially refunded';

    /**
     * Payment processing at Gate
     */
    const PROCESSING_STATUS = 'processing';

    /**
     * An error occurred while reviewing data for payment processing
     */
    const ERROR_STATUS = 'error';

    /**
     * Refund after a successful payment before closing of the business day
     */
    const REVERSED_STATUS = 'reversed';

    /**
     * Returns already parsed gate data
     *
     * @return array
     */
    public function getData();

    /**
     * Get payment info
     *
     * @return ?array
     */
    public function getPayment();

    /**
     * Get payment status
     *
     * @return ?string
     */
    public function getPaymentStatus();

    /**
     * Get payment ID
     *
     * @return ?string
     */
    public function getPaymentId();

    /**
     * Get signature
     *
     * @return string
     */
    public function getSignature();

    /**
     * Cast raw data to array
     *
     * @param string $rawData
     *
     * @return array
     */
    public function toArray($rawData);

    /**
     * Get value by name path
     *
     * @param string $namePath
     *
     * @return mixed
     */
    public function getValue($namePath);

    /**
     * checkSignature
     *
     * @return boolean
     */
    public function checkSignature();

    /**
     * Reads input data from gate
     * @return string
     */
    public static function readData();
}
