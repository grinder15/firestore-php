<?php

namespace Morrislaptop\Firestore\Tests;

use Kreait\Firebase\ServiceAccount;
use Morrislaptop\Firestore\Factory;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected static $fixturesDir = __DIR__ . '/_fixtures';

    /**
     * @var string
     */
    protected static $testCollection;

    /**
     * @var Firebase
     */
    protected static $firebase;

    /**
     * @var Firestore
     */
    protected static $firestore;

    /**
     * @var ServiceAccount
     */
    protected static $serviceAccount;

    public static function setUpBeforeClass()
    {
        self::setUpFirestore();
        self::$testCollection = 'tests';

        try {
            self::$firestore->collection(self::$testCollection)->remove();
        }
        catch (\Exception $e) {
            // assuming it just doesn't exist yet, continue with tests
        }
    }

    public static function setUpFirestore()
    {
        try {
            self::$serviceAccount = ServiceAccount::fromValue('./keyfile.json');
        } catch (\Throwable $e) {
            self::markTestSkipped('The integration tests require FIREBASE_PROJECT_ID, FIREBASE_CLIENT_ID, FIREBASE_CLIENT_EMAIL and FIREBASE_PRIVATE_KEY env variables');
            return;
        }

        self::$firestore = (new Factory())
            ->withServiceAccount(self::$serviceAccount)
            ->createRestFirestore();
    }
}
