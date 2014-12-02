<?php

namespace Erato\Adapter\SymfonyBundles\FrameworkBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('erato_framework');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

		$rootNode
			->addDefaultsIfNotSet()
			->children()
				->append($this->buildCacheFactorySection())
				->append($this->buildCodingSection())
				->append($this->buildAccessorSection())
				->append($this->buildNormalizerSection())
				->append($this->buildMetadataSection())
				->arrayNode('mappings')
					->addDefaultsIfNotSet()
					->children()
						->append($this->buildAttributeMapMappingSection())
						->append($this->buildTagSetMappingSection())
						->append($this->buildAccessorMappingSection())
						->append($this->buildNormalizerMappingSection())
						->append($this->buildSerializerMappingSection())
						->append($this->buildSchemifierMappingSection())
						->append($this->buildSchemaManagerMappingSection())
					->end()
					->end()
				->end()
			->end()
		;

        return $treeBuilder;
    }

	/**
	 * buildCodingSection 
	 *  
	 * coding_standards:
	 *     naming:
	 *         class:           pascal
	 *         property:        camel
	 *         array_field:     snake
	 * 
	 * 
	 * @access protected
	 * @return void
	 */
	protected function buildCodingSection()
	{
		$treeBuilder = new TreeBuilder();
		$node = $treeBuilder->root('coding_standard');

		$node
			->children()
				->arrayNode('naming')
					->children()
						->scalarNode('class')->defaultValue('pascal')->end()
						->scalarNode('property')->defaultValue('camel')->end()
						->scalarNode('array_field')->defaultValue('snake')->end()
					->end()
				->end()
			->end()
		;

		return $node;
	}

	protected function buildCacheFactorySection()
	{
		$treeBuilder = new TreeBuilder();
		$node = $treeBuilder->root('cache_factory');

		$node
			->info('CacheFactory Component')
			->canBeDisabled()
			->addDefaultsIfNotSet()
			->beforeNormalization()
				->ifString()
				->then(function($v){
					return array('id' => $v);	
				})
			->end()
			->children()
				->scalarNode('id')->defaultValue('clio_component.cache.provider_factory')->end()
			->end()
		;

		return $node;

	}

	protected function buildMetadataSection()
	{
		$treeBuilder = new TreeBuilder();
		$node = $treeBuilder->root('metadata');

		$node
			->info('Metadata Configuration')
			->addDefaultsIfNotSet()
			->children()
				->variableNode('config_loader')->defaultValue('annotation')->end()
				->arrayNode('cache')
					->canBeDisabled()
					->addDefaultsIfNotSet()
					->beforeNormalization()
						->ifString()
						->then(function($v){
							return array('type' => 'alias', 'options' => array('id' => $v));	
						})
					->end()
					->children()
						->scalarNode('type')->defaultValue('file_system')->end()
						->scalarNode('ttl')->defaultValue(0)->end()
						->arrayNode('options')
							->defaultValue(array('directory' => '%kernel.cache_dir%/erato_framework/metadata', 'extension' => 'cache.php'))
							->prototype('variable')
							->end()
						->end()
					->end()
		;

		return $node;
	}

	protected function buildAttributeMapMappingSection()
	{
		$treeBuilder = new TreeBuilder();
		$node = $treeBuilder->root('attribute_map');

		$node
			->canBeDisabled()
			->addDefaultsIfNotSet()
			->children()
				->scalarNode('default_class')
					->info('Default attribute class')
					->defaultValue('Clio\Component\Util\Attribute\SimpleAttribute')
				->end()
				->scalarNode('fieldname')
					->info('Default attribute field name')
					->defaultValue('attributes')
				->end()
				->arrayNode('options')
					->defaultValue(array())
					->prototype('variable')->end()
				->end()
			->end()
		;

		return $node;
	}

	protected function buildTagSetMappingSection()
	{
		$treeBuilder = new TreeBuilder();
		$node = $treeBuilder->root('tag_set');

		$node
			->canBeDisabled()
			->addDefaultsIfNotSet()
			->children()
				->scalarNode('default_class')
					->info('Default tag class')
					->defaultValue('Clio\Component\Util\Tag\SimpleTag')
				->end()
				->scalarNode('fieldname')
					->info('Default tag fieldname')
					->defaultValue('tags')
				->end()
				->arrayNode('options')
					->defaultValue(array())
					->prototype('variable')->end()
				->end()
			->end()
		;

		return $node;
	}

	protected function buildSerializerMappingSection()
	{
		$treeBuilder = new TreeBuilder();
		$node = $treeBuilder->root('serializer');

		$node
			->canBeDisabled()
			->addDefaultsIfNotSet()
			->children()
				->scalarNode('default_serializer')->defaultValue('erato_framework.serializer')->end()
				->arrayNode('options')
					->defaultValue(array())
					->prototype('variable')->end()
				->end()
			->end()
		;

		return $node;
	}

	protected function buildAccessorMappingSection()
	{
		$treeBuilder = new TreeBuilder();
		$node = $treeBuilder->root('accessor');

		$node
			->canBeDisabled()
			->addDefaultsIfNotSet()
			->children()
				->arrayNode('options')
					->defaultValue(array())
					->prototype('variable')->end()
				->end()
			->end()
		;

		return $node;
	}

	protected function buildSchemifierMappingSection()
	{
		$treeBuilder = new TreeBuilder();
		$node = $treeBuilder->root('schemifier');

		$node
			->canBeDisabled()
			->addDefaultsIfNotSet()
			->children()
				->scalarNode('default_factory')->defaultValue('erato_framework.schemifier.factory.normalizer')->end()
				->arrayNode('options')
					->defaultValue(array())
					->prototype('variable')->end()
				->end()
			->end()
		;

		return $node;
	}

	protected function buildNormalizerMappingSection()
	{
		$treeBuilder = new TreeBuilder();
		$node = $treeBuilder->root('normalizer');

		$node
			->canBeDisabled()
			->addDefaultsIfNotSet()
			->children()
				->scalarNode('default_normalizer')->defaultValue('erato_framework.normalizer')->end()
				->arrayNode('options')
					->defaultValue(array())
					->prototype('variable')->end()
				->end()
			->end()
		;
		return $node;
	}

	protected function buildSchemaManagerMappingSection()
	{
		$treeBuilder = new TreeBuilder();
		$node = $treeBuilder->root('schema_manager');

		$node
			->canBeDisabled()
			->addDefaultsIfNotSet()
			->children()
				->scalarNode('default_class')->defaultValue('%erato_framework.schema_manager.default.class%')->end()
				->scalarNode('factory_service')->defaultValue('erato_framework.schema_manager.class_factory')->end()
				->arrayNode('options')
					->defaultValue(array())
					->prototype('variable')->end()
				->end()
			->end()
		;
		return $node;
	}
	//

	protected function buildAccessorSection()
	{
		$treeBuilder = new TreeBuilder();
		$node = $treeBuilder->root('accessor');

		$node
			->canBeDisabled()
			->addDefaultsIfNotSet()
			->children()
			->end()
		;

		return $node;
	}

	protected function buildNormalizerSection()
	{
		$treeBuilder = new TreeBuilder();
		$node = $treeBuilder->root('normalizer');

		$node
			->canBeDisabled()
			->addDefaultsIfNotSet()
		;
		$node
			->children()
				->scalarNode('use_clio_normalizer')->defaultTrue()->end()
			->end()
		;

		$strategyNodes = $node
			->children()
				->arrayNode('strategies')
				->addDefaultsIfNotSet()
				->children()
		;

		$defaultStrategies = array(
			'accessor'      => array('enabled' => true, 'priority' => 100),
		);


		foreach($defaultStrategies as $name => $defaults) {
			$this->addNormalizerStrategySection($strategyNodes, $name, $defaults['enabled'], $defaults['priority']);
		}

		return $node;
	}

	protected function addNormalizerStrategySection($parentNode, $name, $isEnabled = true, $priority = null)
	{
		$parentNode
			->arrayNode($name)
				->addDefaultsIfNotSet()
				->treatFalseLike(array('enabled' => false))
				->treatTrueLike(array('enabled' => true))
				->treatTrueLike(array('enabled' => true))
				->treatNullLike(array('enabled' => $isEnabled))
				->beforeNormalization()
					->ifString()
					->then(function($v){
						return array('enabled' => true, 'id' => $v, 'priority' => null);
					})
				->end()
				->children()
					->scalarNode('enabled')->defaultValue($isEnabled)->end()
					->scalarNode('id')->defaultValue('erato_framework.normalizer.default_strategy.' . $name)->end()
					->scalarNode('priority')->defaultValue($priority)->end()
				->end()
		;
	}
}
