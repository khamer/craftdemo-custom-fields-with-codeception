<?php

use craft\elements\Entry;
use craft\elements\User;

class ExampleCest
{
    public function _before(FunctionalTester $I)
    {
    }

    // tests
    public function tryToTest(FunctionalTester $I)
    {
        $section = Craft::$app->sections->getSectionByHandle('blog');
        $entryType = current($section->getEntryTypes());
        $author = User::find()->admin()->one();

        $entry = new Entry([
            'sectionId' => $section->id,
            'typeId' => $entryType->id,
            'fieldLayoutId' => $entryType->fieldLayoutId,
            'authorId' => $author->id,
            'title' => 'Example Blog Article',
            'slug' => 'example-blog-article',
            'postDate' => new DateTime(),
        ]);

        $entry->setFieldValue('bodyContent',
            [
                [
                    'type' => 'richText',
                    'fields' => [
                        'richText' => "Here is some <em>example</em> <strong>rich</strong> text.",
                    ],
                ],
            ],
        );

        $I->saveElement($entry);

        $I->amOnPage("/blog/example-blog-article");
        $I->see("Here is some");
    }
}
