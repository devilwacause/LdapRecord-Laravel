<?php

namespace LdapRecord\Laravel\Hydrators;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use LdapRecord\Models\Model as LdapModel;

class DomainHydrator extends Hydrator
{
    /**
     * @inheritDoc
     */
    public function hydrate(LdapModel $user, EloquentModel $database)
    {
        $database->setLdapDomain($user->getConnectionName());
    }
}
