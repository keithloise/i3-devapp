<?php

namespace {

    use SilverStripe\AssetAdmin\Forms\UploadField;
    use SilverStripe\Assets\Image;
    use SilverStripe\Forms\CheckboxField;
    use SilverStripe\Forms\DropdownField;
    use SilverStripe\Forms\FieldList;
    use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
    use TractorCow\Colorpicker\Color;

    class ImageWithTextOverlay extends Section
    {
        private static $singular_name = 'Image With Text Overlay';

        private static $db = [
            'Content'            => 'HTMLText',
            'ContentPosition'    => 'Varchar',
            'ContentBgColor'     => Color::class,
        ];

        private static $has_one = [
            'Image' => Image::class
        ];

        private static $owns = [
            'Image'
        ];

        public function getSectionCMSFields(FieldList $fields)
        {
            $fields->addFieldToTab('Root.Main', $image = UploadField::create('Image'));
                $image->setFolderName('Sections/Section_ImageWithTextOverlay/Images');
                $image->setAllowedExtensions(['png','gif','jpeg','jpg']);

            $fields->addFieldToTab('Root.Main', HTMLEditorField::create('Content'));
            $fields->addFieldToTab('Root.Main', DropdownField::create('ContentPosition', 'Content position',
                array(
                    'position-left'  => 'Left',
                    'position-right' => 'Right'
                )
            ));
        }
    }
}
