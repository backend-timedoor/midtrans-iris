<?php

namespace Timedoor\TmdMidtransIris;

/**
 * This class just act as an enum
 */
class PayoutStatus
{
    const APPROVED  = 'approved';
    const REJECTED  = 'rejected';
    const PROCESSED = 'processed';
    const COMPLETED = 'completed';
    const FAILED    = 'failed';
    const QUEUED    = 'queued';
}