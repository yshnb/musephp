<?php

/*
 * This file is part of the TerpsichoreOAuth2ServerBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Terpsichore\Bundle\OAuth2ServerBundle\Model;


/**
 * @author Richard Fullmer <richard.fullmer@opensoftdev.com>
 */
interface AuthCodeInterface extends TokenInterface
{
    /**
     * @param string $redirectUri
     */
    function setRedirectUri($redirectUri);
}
