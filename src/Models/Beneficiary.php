<?php

namespace Timedoor\TmdMidtransIris\Models;

use JsonSerializable;
use Timedoor\TmdMidtransIris\Utils\DataMapper;

final class Beneficiary extends DataMapper implements JsonSerializable
{
    /**
     * Account Holder Name
     *
     * @var string
     */
    private $name;
    
    /**
     * Bank
     *
     * @var string
     */
    private $bank;
    
    /**
     * Account Number
     *
     * @var string
     */
    private $account;

    /**
     * Alias Name
     *
     * @var string
     */
    private $aliasName;

    /**
     * Email
     *
     * @var string
     */
    private $email;

    /**
     * Define how data should be serialized using json
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'name'          => $this->name,
            'bank'          => $this->bank,
            'account'       => $this->account,
            'alias_name'    => $this->aliasName,
            'email'         => $this->email
        ]; 
    }

    /**
     * Data mapper describe how the given data should mapped with its setter method
     *
     * @return array
     */
    public function mapper(): array
    {
        return [
            'setName'       => 'name',
            'setBank'       => 'bank',
            'setAccount'    => 'account',
            'setAliasName'  => 'alias_name',
            'setEmail'      => 'email',
        ]; 
    }

    /**
     * Get account Holder Name
     *
     * @return  string
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set account Holder Name
     *
     * @param  string  $name  Account Holder Name
     *
     * @return  self
     */ 
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get bank
     *
     * @return  string
     */ 
    public function getBank()
    {
        return $this->bank;
    }

    /**
     * Set bank
     *
     * @param  string  $bank  Bank
     *
     * @return  self
     */ 
    public function setBank($bank)
    {
        $this->bank = $bank;

        return $this;
    }

    /**
     * Get account Number
     *
     * @return  string
     */ 
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Set account Number
     *
     * @param  string  $account  Account Number
     *
     * @return  self
     */ 
    public function setAccount($account)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get alias Name
     *
     * @return  string
     */ 
    public function getAliasName()
    {
        return $this->aliasName;
    }

    /**
     * Set alias Name
     *
     * @param  string  $aliasName  Alias Name
     *
     * @return  self
     */ 
    public function setAliasName($aliasName)
    {
        $this->aliasName = $aliasName;

        return $this;
    }

    /**
     * Get email
     *
     * @return  string
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set email
     *
     * @param  string  $email  Email
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }
}