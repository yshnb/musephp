<?php
namespace Clio\Adapter\SymfonyBundles\OAuth2ServerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;

/**
 * StorageStrategyFactoryCompilerPass
 *    
 * 
 * @uses CompilerPassInterface
 * @package { PACKAGE }
 * @copyright { COPYRIGHT } (c) { COMPANY }
 * @author Yoshi Aoki <yoshi@44services.jp> 
 * @license { LICENSE }
 */
class StorageStrategyFactoryCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
		$this->processTypeStorageStrategy($container, 'token');
		$this->processTypeStorageStrategy($container, 'user_credentials');
		$this->processTypeStorageStrategy($container, 'client');
	}

	protected function processTypeStorageStrategy(ContainerBuilder $container, $type)
	{
		if($container->hasDefinition('clio_oauth2_server.storage_strategy_factory.' . $type) || $container->hasAlias('clio_oauth2_server.storage_strategy_factory.' . $type)) {
			$typeFactoryDefinition = $container->getDefinition('clio_oauth2_server.storage_strategy_factory.' . $type);
			foreach($container->findTaggedServiceIds('clio_oauth2_server.storage_strategy_factory.' . $type) as $id => $tags) {
				foreach($tags as $tagParams) {
					$typeFactoryDefinition->addMethodCall('setTypeFactory', array($tagParams['for'], new Reference($id)));
				}
			}
		}
    }
}
