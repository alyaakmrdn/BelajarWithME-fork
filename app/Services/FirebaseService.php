<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;
use Kreait\Firebase\Database;

class FirebaseService
{
    protected $auth;
    protected $database;

    public function __construct()
    {
        $serviceAccount = env('FIREBASE_CREDENTIAL_JSON');
        $databaseUrl = env('FIREBASE_DATABASE_URL');

        $factory = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->withDatabaseUri($databaseUrl);

        $this->auth = $factory->createAuth();
        $this->database = $factory->createDatabase();
    }

    public function auth(): Auth
    {
        return $this->auth;
    }

    public function db(): Database
    {
        return $this->database;
    }
}
