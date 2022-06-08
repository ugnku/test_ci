<?php

namespace tci\interfaces;

/**
 * Payment page URL Builder
 */
interface UrlBuilderInterface
{
    const
        PAYMENT_URL_PATTERN = '%s/payment/?%s&signature=%s',
        VALIDATOR_URL_PATTERN = '%s/v1/params/check';

    /**
     * @param string $baseUrl
     * @return static
     */
    public function setBaseUrl($baseUrl);

    /**
     * Get full URL for payment
     *
     * @param PaymentInterface $payment
     * @return string
     */
    public function getUrl(PaymentInterface $payment);

    /**
     * Return full URL for check payment parameters.
     *
     * @return string
     */
    public function getValidationUrl();
}
