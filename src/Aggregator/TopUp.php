<?php

namespace Timedoor\TmdMidtransIris\Aggregator;

use Timedoor\TmdMidtransIris\BaseService;
use Timedoor\TmdMidtransIris\Models\TopUpChannel;
use Timedoor\TmdMidtransIris\Utils\ConvertException;

class TopUp extends BaseService
{
    /**
     * Get available payment channels for top-up
     *
     * @return \Timedoor\TmdMidtransIris\Models\TopUpChannel[]
     */
    public function channels()
    {
        $response = $this->apiClient->get('/channels');

        ConvertException::fromResponse($response);

        return array_map(function ($item) {
            return TopUpChannel::fromArray($item);
        }, $response->getBody() ?? []);
    }
}