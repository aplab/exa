<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 02.08.2018
 * Time: 10:57
 */

namespace App\Component\InstanceEditor;


use App\Component\InstanceEditor\FieldType\FieldTypeFactory;
use App\Component\ModuleMetadata\ModuleMetadata;
use App\Component\ModuleMetadata\ModuleMetadataRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Psr\SimpleCache\InvalidArgumentException;
use ReflectionException;
use Symfony\Component\HttpFoundation\Request;

class InstanceEditor
{
    /**
     * @var object
     */
    protected $entity;

    /**
     * @var InstatceEditorManager
     */
    protected $instanceEditorManager;

    /**
     * @var ModuleMetadataRepository
     */
    protected $moduleMetadataRepository;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManagerInterface;

    /**
     * @var ModuleMetadata
     */
    protected $moduleMetadata;

    /**
     * @var ClassMetadata
     */
    protected $classMetadata;

    /**
     * @var InstanceEditorField[]
     */
    protected $widget;

    /**
     * @var InstanceEditorTab[]
     */
    protected $tab;

    /**
     * InstanceEditor constructor.
     * @param object $entity
     * @param InstatceEditorManager $instance_editor_manager
     * @throws InvalidArgumentException
     * @throws ReflectionException
     */
    public function __construct(object $entity, InstatceEditorManager $instance_editor_manager)
    {
        $this->entity = $entity;
        $this->instanceEditorManager = $instance_editor_manager;
        $this->moduleMetadataRepository = $instance_editor_manager->getModuleMetadataRepository();
        $this->entityManagerInterface = $instance_editor_manager->getEntityManagerInterface();
        $this->moduleMetadata = $this->moduleMetadataRepository->getMetadata($this->entity);
        $this->classMetadata = $this->entityManagerInterface->getClassMetadata($this->moduleMetadata->getClassName());
        $this->configure();
    }

    /**
     * Configrue Instance editor
     */
    protected function configure()
    {
        $this->configureFields();
        $this->configureTabs();
    }

    /**
     * First configure fields
     */
    protected function configureFields()
    {
        $factory = new FieldTypeFactory();
        $this->widget = [];
        $properties = $this->moduleMetadata->getProperties();
        foreach ($properties as $property_name => $property_metadata) {
            $widget_metadata_list = $property_metadata->getWidget();
            $property = $this->classMetadata->getReflectionClass()->getProperty($property_name);
            foreach ($widget_metadata_list as $widget_metadata) {
                $this->widget[] = new InstanceEditorField($this, $property, $property_metadata,
                    $widget_metadata, $factory);
            }
        }
        usort($this->widget, function (InstanceEditorField $a, InstanceEditorField $b) {
            return $a->getOrder() <=> $b->getOrder();
        });
    }

    /**
     * configure tabs
     */
    protected function configureTabs()
    {
        $tab_order_configuration = $this->moduleMetadata->getModule()->getTabOrder();
        $tab_names = [];
        // find required tabs
        foreach ($this->widget as $widget) {
            $tab_name = $widget->getTab();
            if (!isset($tab_names[$tab_name])) {
                $tab_names[$tab_name] = $tab_name;
            }
        }
        // create tabs
        $number = 0;
        foreach ($tab_names as $tab_name) {
            $tab = new InstanceEditorTab;
            $this->tab[] = $tab;
            $tab->setName($tab_name);
            $tab->setOrder($tab_order_configuration[$tab_name] ?? $number++);
        }
        // building tab index
        /**
         * @var InstanceEditorTab[]
         */
        $tab_index = [];
        foreach ($this->tab as $tab) {
            $tab_name = $tab->getName();
            $tab_index[$tab_name] = $tab;
        }
        usort($this->tab, function (InstanceEditorTab $a, InstanceEditorTab $b) {
            return $a->getOrder() <=> $b->getOrder();
        });
        // distribute widgets to tabs
        foreach ($this->widget as $widget) {
            $tab_name = $widget->getTab();
            if (isset($tab_index[$tab_name])) {
                $tab_index[$tab_name]->addField($widget);
            }
        }
    }

    /**
     * @param Request $request
     */
    public function handleRequest(Request $request)
    {
        $data = $request->request->get('apl-instance-editor', []);
        if (empty($data)) {
            return;
        }
        $entity = $this->getEntity();
        foreach ($this->getWidget() as $widget) {
            $property_name = $widget->getPropertyName();
            if (!array_key_exists($property_name, $data)) {
                continue;
            }
            $type = $widget->getType()->getType();
            if ('entity' === $type) {
                $class = $widget->getType()->getEntityClass();
                $repository = $this->entityManagerInterface->getRepository($class);
                $value = $repository->find($data[$property_name]);

            } else {
                $value = $data[$property_name];
            }
            $setter = 'set' . ucfirst($property_name);
            if (method_exists($entity, $setter)) {
                $entity->$setter($value);
            }
        }
        $this->getEntityManagerInterface()->persist($entity);
        $this->getEntityManagerInterface()->flush();
    }

    /**
     * @return object
     */
    public function getEntity(): object
    {
        return $this->entity;
    }

    /**
     * @return ModuleMetadataRepository
     */
    public function getModuleMetadataRepository(): ModuleMetadataRepository
    {
        return $this->moduleMetadataRepository;
    }

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManagerInterface(): EntityManagerInterface
    {
        return $this->entityManagerInterface;
    }

    /**
     * @return ModuleMetadata
     */
    public function getModuleMetadata(): ModuleMetadata
    {
        return $this->moduleMetadata;
    }

    /**
     * @return ClassMetadata
     */
    public function getClassMetadata(): ClassMetadata
    {
        return $this->classMetadata;
    }

    /**
     * @return InstanceEditorField[]
     */
    public function getWidget(): array
    {
        return $this->widget;
    }

    /**
     * @return InstanceEditorTab[]
     */
    public function getTab(): array
    {
        return $this->tab;
    }
}
