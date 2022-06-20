<?php

namespace Timedoor\TmdMidtransIris;

use Timedoor\TmdMidtransIris\Dto\PayoutNotification;
use Timedoor\TmdMidtransIris\Dto\PayoutRequest;
use Timedoor\TmdMidtransIris\Dto\PayoutRequestCollection;
use Timedoor\TmdMidtransIris\Dto\PlainRequest;
use Timedoor\TmdMidtransIris\Models\Payout as PayoutModel;
use Timedoor\TmdMidtransIris\Utils\ConvertException;
use Timedoor\TmdMidtransIris\Utils\Json;
use Timedoor\TmdMidtransIris\Utils\Map;

class Payout extends BaseService
{
    /**
     * Create a new payout
     *
     * @param   \Timedoor\TmdMidtransIris\Dto\PayoutRequest[]
     * @throws  \Timedoor\TmdMidtransIris\Exception\UnauthorizedRequestException|\Timedoor\TmdMidtransIris\Exception\BadRequestException
     * @return  \Timedoor\TmdMidtransIris\Models\Payout[]
     */
    public function create(array $reqs)
    {
        $payloads = array_filter($reqs, function ($item) {
            return $item instanceof PayoutRequest;
        });

        $response = $this
                    ->getApiClient()
                    ->post(
                        '/payouts', (new PayoutRequestCollection)->setPayouts($payloads)
                    );

        ConvertException::fromResponse($response);

        $body = new Map($response->getBody());

        return array_map(function ($item) {
            return PayoutModel::fromArray($item);
        }, $body->get('payouts', []));
    }

    /**
     * Approve an existing payout(s)
     *
     * @param   array       $refNos
     * @param   string|null $otp
     * @throws  \Timedoor\TmdMidtransIris\Exception\UnauthorizedRequestException|\Timedoor\TmdMidtransIris\Exception\BadRequestException
     * @return  array
     */
    public function approve(array $refNos, ?string $otp = null)
    {
        $payload = ['reference_nos' => $refNos];

        if (!is_null($otp)) {
            $payload['otp'] = $otp;
        }

        $response = $this
                    ->getApiClient()
                    ->actingAs(Actor::APPROVER)
                    ->post('/payouts/approve', (new PlainRequest)->setBody($payload));

        ConvertException::fromResponse($response);

        return $response->getBody();
    }

    /**
     * Reject an existing payout(s)
     *
     * @param   array   $refNos
     * @param   string  $reason
     * @throws  \Timedoor\TmdMidtransIris\Exception\UnauthorizedRequestException|\Timedoor\TmdMidtransIris\Exception\BadRequestException
     * @return  array
     */
    public function reject(array $refNos, string $reason)
    {
        $payload = ['reference_nos' => $refNos, 'reject_reason' => $reason];

        $response = $this
                    ->getApiClient()
                    ->actingAs(Actor::APPROVER)
                    ->post('/payouts/reject', (new PlainRequest)->setBody($payload));

        ConvertException::fromResponse($response);

        return $response->getBody();
    }

    /**
     * Get an existing payout by reference number
     *
     * @param   string $refNo
     * @throws  \Timedoor\TmdMidtransIris\Exception\UnauthorizedRequestException|\Timedoor\TmdMidtransIris\Exception\BadRequestException
     * @return  \Timedoor\TmdMidtransIris\Models\Payout
     */
    public function get($refNo)
    {
        $response = $this->getApiClient()->get(sprintf('/payouts/%s', $refNo));

        ConvertException::fromResponse($response);

        return PayoutModel::fromArray($response->getBody() ?? []);
    }

    /**
     * Validate incoming notification
     *
     * @param   string                                              $signature
     * @param   \Timedoor\TmdMidtransIris\Dto\PayoutNotification    $payload
     * @return  boolean
     */
    public function validateNotification($signature, PayoutNotification $payload)
    {
        return $signature === $this->createNotificationSignature($payload);
    }

    /**
     * Create a signature out of payout notification
     *
     * @param   \Timedoor\TmdMidtransIris\Dto\PayoutNotification $payload
     * @return  string
     */
    public function createNotificationSignature(PayoutNotification $payload)
    {
        return hash('sha512', sprintf('%s%s', Json::encode($payload), Config::$merchantKey));
    }
}