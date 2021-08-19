<?php declare(strict_types=1);
/**
 * Copyright (c) 2021 CardGate B.V.
 * All rights reserved.
 * See LICENSE for license details.
 */
namespace CardGate\Shopware\PaymentMethods;

use CardGate\Shopware\Handlers\IdealPaymentHandler;

class Ideal implements PaymentMethodInterface
{
    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getName(): string
    {
        return 'iDEAL';
    }

	/**
	 * {@inheritDoc}
	 *
	 * @return string
	 */
	public function getDescription(): string
	{
		return 'Pay with ' . $this->getName();
	}

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getPaymentHandler(): string
    {
        return IdealPaymentHandler::class;
    }

    /**
     * {@inheritDoc}
     *
     * @return string|null
     */
    public function getTemplate(): ?string
    {
        return '@CuroCardGate/storefront/cardgate/ideal/issuers.html.twig';
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getMedia(): string
    {
	    return __DIR__  . '/../Resources/views/storefront/cardgate/logo/'.strtolower($this->getName()). ".svg";
    }

    /**
     * {@inheritDoc}
     *
     * @return array
     */
    public function getTranslations(): array
    {
	    return [
		    'nl-NL' => [
			    'name'        => $this->getName(),
			    'description' => 'Betaal met '. $this->getName(),
		    ],
		    'de-DE' => [
			    'name'        => $this->getName(),
			    'description' => 'Bezahlen mit '. $this->getName(),
		    ],
		    'en-GB' => [
			    'name'        => $this->getName(),
			    'description' => $this->getDescription(),
		    ],
	    ];
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getType(): string
    {
        return 'direct';
    }
}