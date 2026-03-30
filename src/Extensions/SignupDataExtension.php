<?php

namespace Innoweb\MailChimpSignup\Extensions;

use SilverStripe\Core\Extension;
use SilverStripe\Forms\Validation\RequiredFieldsValidator;
use Psr\SimpleCache\CacheInterface;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\FieldGroup;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\TextareaField;

class SignupDataExtension extends Extension
{
    private static $db = [
        'APIKey' =>  'Varchar(255)',
        'ListID' =>  'Varchar(255)',
        'ContentSuccess' =>  'Text',
        'ContentError' =>  'Text',
        'RequireEmailConfirmation' => 'Boolean',
    ];

    private static $defaults = [
        'ContentSuccess' =>  'The subscription was successful. You will receive a confirmation email shortly.',
        'ContentError' =>  'Unfortunately an error occurred during your subscription. Please try again.',
        'RequireEmailConfirmation' => true,
    ];

    public function updateCMSFields(FieldList $fields)
    {
        /** @TODO SSU RECTOR UPGRADE TASK - FieldList::removeByName: Changed type of parameter $dataFieldOnly in FieldList::removeByName() from dynamic to bool
         * @TODO SSU RECTOR UPGRADE TASK - FieldList::removeByName: Changed type of parameter $fieldName in FieldList::removeByName() from dynamic to string|array
         * @TODO SSU RECTOR UPGRADE TASK - FieldList::removeByName: Changed return type for method FieldList::removeByName() from dynamic to FieldList
         */
        $fields->removeByName([
            'APIKey',
            'ListID',
            'RequireEmailConfirmation',
            'ContentSuccess',
            'ContentError']
        );
        /** @TODO SSU RECTOR UPGRADE TASK - FieldList::addFieldsToTab: Changed type of parameter $fields in FieldList::addFieldsToTab() from dynamic to array
         * @TODO SSU RECTOR UPGRADE TASK - FieldList::addFieldsToTab: Changed type of parameter $insertBefore in FieldList::addFieldsToTab() from dynamic to string|null
         * @TODO SSU RECTOR UPGRADE TASK - FieldList::addFieldsToTab: Changed type of parameter $tabName in FieldList::addFieldsToTab() from dynamic to string
         * @TODO SSU RECTOR UPGRADE TASK - FieldList::addFieldsToTab: Changed return type for method FieldList::addFieldsToTab() from dynamic to FieldList
         */
        $fields->addFieldsToTab(
            'Root.Mailchimp',
            [
                TextField::create(
                    'APIKey',
                    _t('Innoweb\\MailChimpSignup\\Extensions\\SignupDataExtension.APIKEY', 'API Key')
                ),
                TextField::create(
                    'ListID',
                    _t('Innoweb\\MailChimpSignup\\Extensions\\SignupDataExtension.LISTID', 'Audience ID')
                ),
                FieldGroup::create(
                    CheckboxField::create(
                        'RequireEmailConfirmation',
                        _t('Innoweb\\MailChimpSignup\\Extensions\\SignupDataExtension.RequireEmailConfirmation', 'Require Email Confirmation')
                    )
                )->setTitle(_t('Innoweb\\MailChimpSignup\\Extensions\\SignupDataExtension.EmailConfirmation', 'Email Confirmation')),
                TextareaField::create(
                    'ContentSuccess',
                    _t('Innoweb\\MailChimpSignup\\Extensions\\SignupDataExtension.CONTENTSUCCESS', 'Text for successful submission')
                ),
                TextareaField::create(
                    'ContentError',
                    _t('Innoweb\\MailChimpSignup\\Extensions\\SignupDataExtension.CONTENTERROR', 'Text for unsuccessful submission')
                )
            ]
        );
    }

    public function getCMSValidator()
    {
        return RequiredFieldsValidator::create('APIKey', 'ListID');
    }

    public function onAfterWrite()
    {
        // clear form field cache
        $cache = Injector::inst()->get(CacheInterface::class . '.MailchimpFieldCache');
        $cache->clear();
    }

    public function onAfterPublish()
    {
        // clear form field cache
        $cache = Injector::inst()->get(CacheInterface::class . '.MailchimpFieldCache');
        $cache->clear();
    }
}