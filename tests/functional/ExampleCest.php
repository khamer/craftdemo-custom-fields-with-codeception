<?php

use craft\elements\Entry;
use craft\elements\User;

class ExampleCest
{
    public function _before(FunctionalTester $I)
    {
    }

    /**
     * This test passes.
     */
    public function testRedactorInMatrix(FunctionalTester $I)
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

    /**
     * This test fails - I don't even see the block created in the CMS.
     */
    public function testRedactorInNeo(FunctionalTester $I)
    {
        $section = Craft::$app->sections->getSectionByHandle('blog');
        $entryType = current($section->getEntryTypes());
        $author = User::find()->admin()->one();

        $entry = new Entry([
            'sectionId' => $section->id,
            'typeId' => $entryType->id,
            'fieldLayoutId' => $entryType->fieldLayoutId,
            'authorId' => $author->id,
            'title' => 'Another Blog Article',
            'slug' => 'another-blog-article',
            'postDate' => new DateTime(),
        ]);

        $entry->setFieldValue('neoContent',
            [
                'blocks' => [
                    'new1' => [
                        'modified' => 1,
                        'type' => 'richText',
                        'level' => 0,
                        'enabled' => true,
                        'collapsed' => false,
                        'fields' => [
                            'neoRichText' => "Here is more <em>example</em> <strong>rich</strong> text.",
                        ],
                    ],
                ],
            ],
        );

        $I->saveElement($entry);

        $I->amOnPage("/blog/another-blog-article");
        $I->see("Here is more");
    }
}
