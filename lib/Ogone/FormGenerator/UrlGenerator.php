<?php

/*
 * This file is part of the Marlon Ogone package.
 *
 * (c) Marlon BVBA <info@marlon.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ogone\FormGenerator;

use Ogone\PaymentRequest;

/**
 * Creates an url that can be used to redirect the user to Ogone. It can also be used to show a link to Ogone.
 *
 * @author  Joris van de Sande <joris.van.de.sande@freshheads.com>
 */
class UrlGenerator implements FormGenerator
{

    /**
     * @param PaymentRequest $paymentRequest
     *
     * @return string url
     */
    public function render(PaymentRequest $paymentRequest)
    {
        $parameters = $paymentRequest->toArray();

        $parameters['SHASIGN'] = $paymentRequest->getShaSign();

        return $paymentRequest->getOgoneUri() . '?' . http_build_query($parameters);
    }
}
