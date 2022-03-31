<?php

namespace Timedoor\TmdMidtransIris;

use Timedoor\TmdMidtransIris\Dto\PayoutRequest;
use Timedoor\TmdMidtransIris\Dto\PayoutRequestCollection;
use Timedoor\TmdMidtransIris\Dto\PlainRequest;
use Timedoor\TmdMidtransIris\Utils\ConvertException;

class Payout extends BaseService
{
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

        return $response;
    }

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

        return $response;
    }

    public function reject(array $refNos, string $reason)
    {
        $payload = ['reference_nos' => $refNos, 'reject_reason' => $reason];

        $response = $this
                    ->getApiClient()
                    ->post('/payouts/reject', (new PlainRequest)->setBody($payload));

        ConvertException::fromResponse($response);

        return $response;
    }

    public function get($refNo)
    {
        $response = $this->getApiClient()->get(sprintf('/payouts/%s', $refNo));

        ConvertException::fromResponse($response);

        return $response;
    }
}