<?php

namespace {

    use SilverStripe\Forms\CheckboxField;
    use SilverStripe\Forms\HiddenField;
    use SilverStripe\Forms\TextField;
    use SilverStripe\ORM\DataObject;

    class QISessionSubCategory extends DataObject
    {
        private static $default_sort = 'Sort ASC';

        private static $db = [
            'Name'     => 'Varchar',
            'Archived' => 'Boolean',
            'Sort'     => 'Int'
        ];

        private static $belongs_many_many = [
            'QISessionPage' => QISessionPage::class
        ];

        private static $summary_fields = [
            'Name',
            'Status',
        ];

        public function getCMSFields()
        {
            $fields = parent::getCMSFields(); // TODO: Change the autogenerated stub
            $fields->addFieldToTab('Root.Main', TextField::create('Name', 'Category Name'));
            $fields->addFieldToTab('Root.Main', CheckboxField::create('Archived'));
            $fields->addFieldToTab('Root.Main', HiddenField::create('Sort'));

            return $fields;
        }

        public function getStatus()
        {
            if($this->Archived == 1) return _t('GridField.Archived', 'Archived');
            return _t('GridField.Live', 'Live');
        }
    }
}
