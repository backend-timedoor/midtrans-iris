<?php

namespace Timedoor\TmdMidtransIris;

use Timedoor\TmdMidtransIris\Dto\PayoutNotification;
use Timedoor\TmdMidtransIris\Dto\PayoutRequest;
use Timedoor\TmdMidtransIris\Dto\PayoutRequestCollection;
use Timedoor\TmdMidtransIris\Dto\PlainRequest;
use Timedoor\TmdMidtransIris\Exception\BadRequestException;
use Timedoor\TmdMidtransIris\Exception\UnauthorizedRequestException;
use Timedoor\TmdMidtransIris\Models\Payout as PayoutModel;
use Timedoor\TmdMidtransIris\Utils\Arr;
use Timedoor\TmdMidtransIris\Utils\ConvertException;
use Timedoor\TmdMidtransIris\Utils\Json;

class Payout extends BaseService
{
    /**
     * Create a new payout
     *
     * @param   PayoutRequest[]
     * @throws  UnauthorizedRequestException|BadRequestException
     * @return  PayoutModel[]
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

        $body   = $response->getBody();
        $result = [];

        foreach ($body['payouts'] as $item) {
            $item       = new Arr($item);
            $result[]   = (new PayoutModel)
                            ->setRefNo($item->get('reference_no'))
                            ->setStatus($item->get('status'));
        }

        return $result;
    }

    /**
     * Approve an existing payout(s)
     *
     * @param   array       $refNos
     * @param   string|null $otp
     * @throws  UnauthorizedRequestException|BadRequestException
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
                    ->post('/payouts/approve', (new PlainRequest)->setBody($payload));

        ConvertException::fromResponse($response);

        return $response->getBody();
    }

    /**
     * Reject an existing payout(s)
     *
     * @param   array $refNos
     * @param   string $reason
     * @throws  UnauthorizedRequestException|BadRequestException
     * @return  array
     */
    public function reject(array $refNos, string $reason)
    {
        $payload = ['reference_nos' => $refNos, 'reject_reason' => $reason];

        $response = $this
                    ->getApiClient()
                    ->post('/payouts/reject', (new PlainRequest)->setBody($payload));

        ConvertException::fromResponse($response);

        return $response->getBody();
    }

    /**
     * Get an existing payout by reference number
     *
     * @param   string $refNo
     * @throws  UnauthorizedRequestException|BadRequestException
     * @return  PayoutModel
     */
    public function get($refNo)
    {
        $response = $this->getApiClient()->get(sprintf('/payouts/%s', $refNo));

        ConvertException::fromResponse($response);

        $body   = new Arr($response->getBody());
        $result = (new PayoutModel)
                    ->setAmount($body->get('amount'))
                    ->setBeneficiaryName($body->get('beneficiary_name'))
                    ->setBeneficiaryAccount($body->get('beneficiary_account'))
                    ->setBeneficiaryEmail($body->get('beneficiary_email'))
                    ->setBank($body->get('bank'))
                    ->setRefNo($body->get('reference_no'))
                    ->setNotes($body->get('notes'))
                    ->setStatus($body->get('status'))
                    ->setCreatedBy($body->get('created_by'))
                    ->setCreatedAt($body->get('created_at'))
                    ->setUpdatedAt($body->get('updated_at'));

        return $result;
    }

    /**
     * Validate incoming notification
     *
     * @param   string              $signature
     * @param   PayoutNotification  $payload
     * @return  boolean
     */
    public function validateNotification($signature, PayoutNotification $payload)
    {
        return $signature === $this->createNotificationSignature($payload);
    }

    /**
     * Create a signature out of payout notification
     *
     * @param   PayoutNotification $payload
     * @return  string
     */
    public function createNotificationSignature(PayoutNotification $payload)
    {
        return hash('sha512', sprintf('%s%s', Json::encode($payload), Config::$merchantKey));
    }
}