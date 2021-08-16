<?php declare(strict_types=1);

namespace CardGate\Shopware\Subscribers;


use cardgate\api;
use CardGate\Shopware\Helper\ApiHelper;
use CardGate\Shopware\Helper\CgtHelper;
use CardGate\Shopware\Storefront\Struct\CardGateStruct;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Storefront\Page\Checkout\Confirm\CheckoutConfirmPageLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CheckoutConfirmTemplateSubscriber implements EventSubscriberInterface
{
    /** @var ApiHelper */
    private $apiHelper;
    private $cgtHelper;
    private $customerRepository;
	private $shopwareVersion;

    /**
     * CheckoutConfirmTemplateSubscriber constructor.
     * @param ApiHelper $apiHelper
     * @param MspHelper $cgtHelper
     * @param EntityRepositoryInterface $customerRepository
     */
    public function __construct(
        ApiHelper $apiHelper,
        CgtHelper $cgtHelper,
        EntityRepositoryInterface $customerRepository,
	    string $shopwareVersion
    ) {
        $this->apiHelper = $apiHelper;
        $this->cgtHelper = $cgtHelper;
        $this->customerRepository = $customerRepository;
        $this->shopwareVersion = $shopwareVersion;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            CheckoutConfirmPageLoadedEvent::class => 'addCardGateExtension'
        ];
    }


    /**
     * @param CheckoutConfirmPageLoadedEvent $event
     * @throws \Exception
     */
    public function addCardGateExtension(CheckoutConfirmPageLoadedEvent $event): void
    {
    	$salesChannelContext = $event->getSalesChannelContext();
        $customer = $salesChannelContext->getCustomer();
        $client = $this->apiHelper->initializeCardGateClient($salesChannelContext->getSalesChannel()->getId());
        $struct = new CardGateStruct();

	    $issuers = $client->methods()->get( \cardgate\api\Method::IDEAL )->getIssuers();
        $lastUsedIssuer = $customer->getCustomFields()['last_used_issuer'] ?? null;

        $struct->assign([
            'issuers' => $issuers,
            'last_used_issuer' => $lastUsedIssuer,
            'payment_method_name' => $activeName ?? null,
            'is_guest' => $customer->getGuest(),
            'current_payment_method_id' => $event->getSalesChannelContext()->getPaymentMethod()->getId()
        ]);
        $event->getPage()->addExtension(
            CardGateStruct::EXTENSION_NAME,
            $struct
        );
    }
}
