<?php

declare(strict_types=1);

/*
 * This file is part of Mindy Framework.
 * (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\MenuBundle\Model;

use Mindy\Orm\Fields\CharField;
use Mindy\Orm\TreeModel;

/**
 * Class Menu.
 *
 * @property string $slug
 * @property string $name
 * @property string $url
 */
class Menu extends TreeModel
{
    public static function getFields()
    {
        return array_merge(parent::getFields(), [
            'name' => [
                'class' => CharField::class,
                'verboseName' => 'Название',
            ],
            'url' => [
                'class' => CharField::class,
                'null' => true,
                'verboseName' => 'Ссылка',
            ],
        ]);
    }

    public function __toString()
    {
        return (string) $this->name;
    }
}
