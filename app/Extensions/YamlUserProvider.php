<?php

namespace App\Extensions;

use App\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Filesystem\Filesystem;
use Hautelook\Phpass\PasswordHash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Yaml\Yaml;

/**
 * This class implements partial support for local yaml users in order to support an automated migration into
 * a local database user. It is not supported for use other than migration.
 */
class YamlUserProvider implements UserProvider
{
    private Filesystem $disk;
    private PasswordHash $hasher;

    /**
     * @param string $path The fully qualified path to load YAML users from
     */
    public function __construct(string $path)
    {
        $this->disk = Storage::build([
            'driver' => 'local',
            'root' => $path,
        ]);
        $this->hasher = new PasswordHash(8, true);
    }


    /**
     * @inheritDoc
     */
    public function retrieveById($identifier)
    {
        // TODO: Implement retrieveById() method.
    }

    /**
     * @inheritDoc
     */
    public function retrieveByToken($identifier, $token)
    {
        // TODO: Implement retrieveByToken() method.
    }

    /**
     * @inheritDoc
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
        // TODO: Implement updateRememberToken() method.
    }

    /**
     * @inheritDoc
     */
    public function retrieveByCredentials(array $credentials)
    {
        $email = $credentials['email'];
        if (!Str::endsWith($email, '@localhost')) {
            return null; // YAML users should always have this host suffix when they are being converted.
        }

        $username = explode('@', $email, 2);

        if (!$this->disk->exists("{$username[0]}.yml")) return null;

        $size = $this->disk->size("{$username[0]}.yml");
        $yamlStream = $this->disk->readStream("{$username[0]}.yml");
        $yamlData = fread($yamlStream, $size);
        $data = Yaml::parse($yamlData);
        if (!isset($data['password_hash'])) {
            throw new \Exception('Malformed local user yaml file does not contain `password_hash`');
        }

        $user = new User();
        $user->email = $email;
        $user->password = $data['password_hash'];

        return $user;
    }

    /**
     * @inheritDoc
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return $this->hasher->CheckPassword($credentials['password'], $user->password);
    }
}
