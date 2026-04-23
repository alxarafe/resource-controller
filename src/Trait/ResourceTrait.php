<?php

declare(strict_types=1);

/*
 * Copyright (C) 2024-2026 Rafael San José <rsanjose@alxarafe.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

namespace Alxarafe\ResourceController\Trait;

use Alxarafe\ResourceController\ResourceInterface;
use Alxarafe\ResourceController\Contracts\RepositoryContract;
use Alxarafe\ResourceController\Contracts\TransactionContract;
use Alxarafe\ResourceController\Contracts\RelationContract;
use Alxarafe\ResourceController\Contracts\TranslatorContract;
use Alxarafe\ResourceController\Contracts\MessageBagContract;
use Alxarafe\ResourceController\Contracts\HookContract;
use Alxarafe\ResourceController\Contracts\RendererContract;
use Alxarafe\ResourceController\Component\AbstractField;
use Alxarafe\ResourceController\Component\AbstractFilter;
use Alxarafe\ResourceController\Component\Container\Panel;
use Alxarafe\ResourceController\Component\Container\Tab;
use Alxarafe\ResourceController\Component\Container\TabGroup;
use Alxarafe\ResourceController\Component\Fields;

/**
 * ResourceTrait — ORM-agnostic declarative CRUD controller logic.
 *
 * All data access goes through RepositoryContract.
 * All translations through TranslatorContract.
 * All messages through MessageBagContract.
 * All hooks through HookContract.
 *
 * The host controller must implement the abstract methods to provide
 * these dependencies and define the resource configuration.
 */
trait ResourceTrait
{
    // ── State ──────────────────────────────────────────────────────

    public string $mode = ResourceInterface::MODE_LIST;
    public ?string $recordId = null;
    protected bool $useTabs = false;
    protected int $offset = 0;
    protected string $activeTab = '';
    public bool $protectChanges = false;

    /** @var array Configuration structure */
    protected array $structConfig = [
        'list' => ['tabs' => [], 'head_buttons' => [], 'row_actions' => [], 'limit' => 50],
        'edit' => ['sections' => [], 'head_buttons' => [], 'actions' => []],
    ];

    // ── Abstract: Dependencies (must be provided by host) ─────────

    abstract protected function getTranslator(): TranslatorContract;
    abstract protected function getMessages(): MessageBagContract;
    abstract protected function getHooks(): HookContract;
    abstract protected function getTransaction(): TransactionContract;

    /**
     * Return a RepositoryContract for the given tab.
     * For single-model controllers, ignore $tabId.
     */
    abstract protected function getRepository(string $tabId = 'default'): RepositoryContract;

    /**
     * Optional: return a RelationContract for saving child records.
     */
    protected function getRelationHandler(): ?RelationContract
    {
        return null;
    }

    /**
     * Host must provide module/controller names for URL generation and hooks.
     */
    abstract public static function getModuleName(): string;
    abstract public static function getControllerName(): string;
    abstract public static function url(string $action = 'index', array $params = []): string;

    // ── Abstract: Resource Definition ─────────────────────────────

    protected function getListColumns(): array
    {
 return []; 
}
    protected function getEditFields(): array
    {
 return []; 
}
    protected function getFilters(): array
    {
 return []; 
}
    protected function getTabVisibility(): array
    {
 return []; 
}
    protected function getTabBadges(): array
    {
 return []; 
}

    protected function getTabs(): array
    {
        $fields = $this->getEditFields();
        $visibility = $this->getTabVisibility();
        $t = $this->getTranslator();
        $tabs = [];

        foreach ($fields as $key => $data) {
            if (isset($visibility[$key]) && !call_user_func($visibility[$key])) {
                continue;
            }
            if (is_array($data) && isset($data['fields'])) {
                $tabs[] = new Tab($key, $data['label'] ?? $t->translate($key), '', $data['fields']);
            } elseif (is_array($data)) {
                $tabs[] = new Tab($key, $t->translate($key), '', $data);
            }
        }

        $badges = $this->getTabBadges();
        foreach ($tabs as $tab) {
            $key = str_replace('tab_', '', $tab->getTabId());
            if (isset($badges[$key])) {
                $count = call_user_func($badges[$key]);
                if ($count !== null) {
                    $tab->setBadgeCount($count);
                }
            }
        }
        return $tabs;
    }

    protected function insertTabAfter(array $tabs, string $afterKey, Tab $newTab): array
    {
        $targetId = 'tab_' . $afterKey;
        foreach ($tabs as $i => $tab) {
            if ($tab->getTabId() === $targetId) {
                array_splice($tabs, $i + 1, 0, [$newTab]);
                return $tabs;
            }
        }
        $tabs[] = $newTab;
        return $tabs;
    }

    // ── Lifecycle ─────────────────────────────────────────────────

    protected function privateCore(): void
    {
        $this->detectMode();
        $this->beforeConfig();
        $this->buildConfiguration();
        $this->setup();

        if ($this->mode === ResourceInterface::MODE_LIST) {
            $this->beforeList();
        } elseif ($this->mode === ResourceInterface::MODE_EDIT) {
            $this->beforeEdit();
        }

        $this->handleRequest();
    }

    protected function detectMode(): void
    {
        $this->recordId = $_GET['id'] ?? $_POST['id'] ?? $_GET['code'] ?? null;
        $this->mode = $this->recordId ? ResourceInterface::MODE_EDIT : ResourceInterface::MODE_LIST;
        if ($this->recordId) {
            $this->protectChanges = true;
        }
    }

    // ── Lifecycle Hooks ───────────────────────────────────────────

    protected function beforeConfig(): void
    {
}
    protected function beforeList(): void
    {
}
    protected function beforeEdit(): void
    {
}
    protected function afterSaveRecord(array $savedRecord, array $submittedData): void
    {
}

    // ── Setup (default buttons) ───────────────────────────────────

    protected function setup(): void
    {
        $t = $this->getTranslator();

        $this->addListButton(
            'new', $t->translate('new'), 'fas fa-plus', 'primary', 'right', 'url',
            'index.php?module=' . static::getModuleName() . '&controller=' . static::getControllerName() . '&id=new');

        $this->addEditButton('save', $t->translate('save_changes'), 'fas fa-save', 'primary', 'right', 'submit');
        $this->addEditButton('back', $t->translate('back'), 'fas fa-arrow-left', 'secondary', 'right', 'url', static::url());
    }

    // ── Request Handling ──────────────────────────────────────────

    protected function handleRequest(): void
    {
        if (isset($_GET['offset'])) {
            $this->offset = (int) $_GET['offset'];
        }

        if ($this->mode === ResourceInterface::MODE_LIST) {
            $this->activeTab = $_GET['tab'] ?? array_key_first($this->structConfig['list']['tabs'] ?? []) ?? '';
            if (isset($_GET['ajax']) && $_GET['ajax'] === 'get_data') {
                $this->jsonResponse($this->fetchListData($this->activeTab));
                return;
            }
        }

        if ($this->mode === ResourceInterface::MODE_EDIT) {
            if (isset($_GET['ajax']) && $_GET['ajax'] === 'get_record') {
                $this->jsonResponse($this->fetchRecordData());
                return;
            }
            if (
                (isset($_POST['action']) && $_POST['action'] === 'save') ||
                (isset($_GET['ajax']) && $_GET['ajax'] === 'save_record')
            ) {
                $this->saveRecord();
            }
        }
    }

    // ── Data: List ────────────────────────────────────────────────

    protected function fetchListData(string $tabId): array
    {
        if (!isset($this->structConfig['list']['tabs'][$tabId])) {
            return ['error' => $this->getTranslator()->translate('resource_invalid_tab')];
        }

        $tabConfig = $this->structConfig['list']['tabs'][$tabId];
        $repo = $this->getRepository($tabId);
        $limit = $this->structConfig['list']['limit'];

        try {
            $query = $repo->query();

            // Tab conditions
            foreach ($tabConfig['conditions'] ?? [] as $key => $val) {
                if ($val === null) {
                    $query->whereNull($key);
                } elseif ($val === 'NOT NULL') {
                    $query->whereNotNull($key);
                } else {
                    $query->where($key, '=', $val);
                }
            }

            // Global search
            $globalQuery = $_GET['q'] ?? null;
            if ($globalQuery && !empty($this->globalSearchFields ?? [])) {
                $query->search($this->globalSearchFields, $globalQuery);
            }

            // Filters
            foreach ($tabConfig['filters'] ?? [] as $filter) {
                $paramName = 'filter_' . $tabId . '_' . $filter->getField();
                $value = $_GET[$paramName] ?? null;
                if ($value !== null && $value !== '') {
                    $filter->apply($query, $value);
                }
            }

            // Eager loading
            if (!empty($this->with ?? [])) {
                $query->with($this->with);
            }

            $query->orderBy($repo->getPrimaryKey(), 'DESC');
            $result = $query->paginate($limit, $this->offset);

            return [
                'data' => $this->processResults($result->items, $tabConfig['columns'] ?? []),
                'meta' => ['total' => $result->total, 'limit' => $result->limit, 'offset' => $result->offset],
            ];
        } catch (\Exception $e) {
            return ['error' => $this->getTranslator()->translate('database_error', ['message' => $e->getMessage()])];
        }
    }

    protected function processResults(array $items, array $columns): array
    {
        $t = $this->getTranslator();
        $results = [];

        foreach ($items as $row) {
            $processed = $row;
            foreach ($columns as $col) {
                if ($col instanceof AbstractField) {
                    $col = $col->jsonSerialize();
                }
                $field = $col['field'] ?? '';

                // Auto-translate values starting with '#'
                if (isset($processed[$field]) && is_string($processed[$field]) && str_starts_with($processed[$field], '#')) {
                    $processed[$field] = $t->translate($processed[$field]);
                }
                // Boolean casting
                if (($col['type'] ?? '') === 'boolean' || ($col['component'] ?? '') === 'boolean') {
                    $processed[$field] = (bool) ($processed[$field] ?? false);
                }
            }
            $results[] = $processed;
        }
        return $results;
    }

    // ── Data: Single Record ───────────────────────────────────────

    protected function fetchRecordData(): array
    {
        $t = $this->getTranslator();
        if (!$this->recordId) {
            return ['error' => $t->translate('resource_no_id_provided')];
        }

        $repo = $this->getRepository();

        if ($this->recordId === 'new') {
            return ['id' => 'new', 'data' => $repo->newRecord(), 'meta' => ['is_new' => true]];
        }

        $data = $repo->find($this->recordId);
        if (!$data) {
            return ['error' => $t->translate('record_not_found')];
        }

        return ['id' => $this->recordId, 'data' => $data];
    }

    // ── Data: Save ────────────────────────────────────────────────

    protected function saveRecord(): void
    {
        $t = $this->getTranslator();
        $messages = $this->getMessages();
        $hooks = $this->getHooks();

        // Parse input
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        $rawInput = file_get_contents('php://input');
        $isAjax = str_contains($contentType, 'application/json') || isset($_GET['ajax']);

        if (str_contains($contentType, 'application/json') || ($rawInput && $rawInput[0] === '{')) {
            $json = json_decode($rawInput, true);
            if (is_array($json)) {
                $_POST = array_merge($_POST, $json);
            }
        }

        $data = $_POST['data'] ?? [];
        if (empty($data)) {
            $messages->error($t->translate('no_data_provided'));
            $this->respondToSave(['status' => 'error', 'error' => $t->translate('no_data_provided')], $isAjax);
            return;
        }

        $repo = $this->getRepository();
        $tx = $this->getTransaction();

        // Separate main data from relation data
        $modelData = [];
        $relationData = [];
        $fieldDefs = $this->collectFieldDefinitions();

        foreach ($data as $key => $value) {
            if (isset($fieldDefs[$key]) && $fieldDefs[$key]->getType() === 'relation_list') {
                $relationData[$key] = $value;
            } else {
                $modelData[$key] = $value;
            }
        }

        $tx->begin();
        try {
            $hooks->execute('before_save.' . static::getControllerName(), $modelData);

            $id = ($this->recordId && $this->recordId !== 'new') ? $this->recordId : null;
            /** @var array<string, mixed> $modelData */
            $savedRecord = $repo->save($id, $modelData);

            // Save relations if handler available
            $relationHandler = $this->getRelationHandler();
            if ($relationHandler && !empty($relationData)) {
                $savedId = $savedRecord[$repo->getPrimaryKey()] ?? null;
                if ($savedId) {
                    foreach ($relationData as $relationName => $rows) {
                        $relationHandler->syncRelation($savedId, $relationName, $rows, []);
                    }
                }
            }

            $tx->commit();

            $messages->success($t->translate('record_saved'));
            $this->afterSaveRecord($savedRecord, $data);
            $hooks->execute('after_save.' . static::getControllerName(), $savedRecord, $data);

            $this->respondToSave([
                'status' => 'success',
                'id' => $savedRecord[$repo->getPrimaryKey()] ?? '',
                'data' => $savedRecord,
            ], $isAjax);
        } catch (\Throwable $e) {
            $tx->rollback();
            $messages->error($e->getMessage() ?: $t->translate('error_occurred'));
            $this->respondToSave(['status' => 'error', 'error' => $e->getMessage()], $isAjax);
        }
    }

    protected function respondToSave(array $response, bool $isAjax): void
    {
        if ($isAjax) {
            if (!isset($response['messages'])) {
                $response['messages'] = $this->getMessages()->getMessages();
            }
            $this->jsonResponse($response);
            return;
        }

        if (($response['status'] ?? '') === 'success') {
            $this->redirect(static::url('', ['id' => $response['id'] ?? '']));
        } else {
            $this->redirect($_SERVER['HTTP_REFERER'] ?? static::url('index'));
        }
    }

    // ── Configuration Builder ─────────────────────────────────────

    protected function buildConfiguration(): void
    {
        $repo = $this->getRepository();
        $defaultTab = 'general';
        $this->addListTab($defaultTab, 'General', []);

        // List columns
        $columns = method_exists($this, 'getListFields') ? $this->getListFields() : $this->getListColumns();
        if (empty($columns)) {
            $columns = $this->convertMetadataToComponents($repo->getFieldMetadata());
        }
        if (!empty($columns)) {
            $this->setListColumns($columns, $defaultTab);
        }

        // Filters
        foreach ($this->getFilters() as $filter) {
            if ($filter instanceof AbstractFilter) {
                $this->addListFilter($defaultTab, $filter);
            }
        }

        // Edit fields
        $fields = $this->getEditFields();
        if (empty($fields)) {
            $fields = $this->convertMetadataToComponents($repo->getFieldMetadata());
        }

        // Normalize to sections
        $tabsConfig = [];
        if (!empty($fields)) {
            $isStructured = false;
            foreach ($fields as $v) {
                if (is_array($v) && isset($v['fields'])) {
                    $isStructured = true;
                    break;
                }
            }

            if (!$isStructured) {
                $tabsConfig['main'] = ['label' => 'General', 'fields' => $fields];
            } else {
                foreach ($fields as $key => $tabData) {
                    if (is_array($tabData) && isset($tabData['fields'])) {
                        $tabsConfig[$key] = [
                            'label' => $tabData['label'] ?? ucfirst($key),
                            'fields' => $tabData['fields'],
                        ];
                    }
                }
            }
        }

        // Apply sections
        $visibility = $this->getTabVisibility();
        foreach ($tabsConfig as $sectionId => $sectionData) {
            if (isset($visibility[$sectionId]) && !call_user_func($visibility[$sectionId])) {
                continue;
            }
            $this->addEditSection($sectionId, $sectionData['label']);

            $sectionFields = array_values(array_filter($sectionData['fields'], function ($f) {
                return !($f instanceof AbstractField) || $f->isVisible();
            }));

            // Hook: allow plugins to modify fields
            $hookName = 'form_fields_after.' . static::getControllerName();
            $sectionFields = $this->getHooks()->filter($hookName, $sectionFields, $this);

            $this->setEditFields($sectionFields, $sectionId);
        }
    }

    protected function convertMetadataToComponents(array $metadata): array
    {
        $components = [];
        foreach ($metadata as $fieldData) {
            $field = $fieldData['field'] ?? '';
            if (empty($field) || in_array($field, ['id', 'created_at', 'updated_at', 'deleted_at'])) {
                continue;
            }

            $label = $fieldData['label'] ?? ucfirst($field);
            $type = $fieldData['genericType'] ?? 'text';
            $options = ['required' => $fieldData['required'] ?? false];

            if (!empty($fieldData['length']) && is_numeric($fieldData['length'])) {
                $options['maxlength'] = (int) $fieldData['length'];
            }

            $components[] = match ($type) {
                'boolean'  => new Fields\Boolean($field, $label, $options),
                'date'     => new Fields\Date($field, $label, $options),
                'datetime' => new Fields\DateTime($field, $label, $options),
                'time'     => new Fields\Time($field, $label, $options),
                'integer'  => new Fields\Integer($field, $label, $options),
                'decimal'  => new Fields\Decimal($field, $label, $options),
                'textarea' => new Fields\Textarea($field, $label, $options),
                default    => new Fields\Text($field, $label, $options),
            };
        }
        return $components;
    }

    // ── Config Helpers ─────────────────────────────────────────────

    protected function addListButton(string $name, string $label, string $icon, string $type = 'primary', string $location = 'right', string $action = 'url', string $target = ''): void
    {
        $this->structConfig['list']['head_buttons'][] = compact('name', 'label', 'icon', 'type', 'location', 'action', 'target');
    }

    protected function addEditButton(string $name, string $label, string $icon, string $type = 'secondary', string $location = 'right', string $action = 'url', string $target = ''): void
    {
        $this->structConfig['edit']['head_buttons'][] = compact('name', 'label', 'icon', 'type', 'location', 'action', 'target');
    }

    protected function addRowAction(string $name, string $label, string $icon, string $action = 'url'): void
    {
        $this->structConfig['list']['row_actions'][] = compact('name', 'label', 'icon', 'action');
    }

    protected function addListTab(string $id, string $title, array $conditions = []): void
    {
        $this->structConfig['list']['tabs'][$id] = ['title' => $title, 'columns' => [], 'filters' => [], 'conditions' => $conditions];
        if (empty($this->activeTab)) {
            $this->activeTab = $id;
        }
    }

    protected function addListColumn(string $tabId, AbstractField|string $fieldOrColumn, string $label = '', string $type = 'text', array $options = []): void
    {
        if (!isset($this->structConfig['list']['tabs'][$tabId])) return;
        if ($fieldOrColumn instanceof AbstractField) {
            $this->structConfig['list']['tabs'][$tabId]['columns'][] = $fieldOrColumn;
        } else {
            $this->structConfig['list']['tabs'][$tabId]['columns'][] = array_merge(
                ['field' => $fieldOrColumn, 'label' => $label, 'type' => $type, 'visible' => true], $options
            );
        }
    }

    protected function addEditSection(string $id, string $title): void
    {
        $this->structConfig['edit']['sections'][$id] = ['title' => $title, 'fields' => []];
    }

    protected function addEditField(string $sectionId, AbstractField|string $fieldOrObject, string $label = '', string $type = 'text', array $options = []): void
    {
        if (!isset($this->structConfig['edit']['sections'][$sectionId])) return;
        if ($fieldOrObject instanceof AbstractField) {
            $this->structConfig['edit']['sections'][$sectionId]['fields'][] = $fieldOrObject;
        } else {
            $this->structConfig['edit']['sections'][$sectionId]['fields'][] = array_merge(
                ['field' => $fieldOrObject, 'label' => $label, 'type' => $type, 'visible' => true], $options
            );
        }
    }

    protected function addListFilter(string $tabId, AbstractFilter $filter): void
    {
        if (!isset($this->structConfig['list']['tabs'][$tabId])) return;
        $this->structConfig['list']['tabs'][$tabId]['filters'][] = $filter;
    }

    protected function setListColumns(array $columns, string $tabId = ''): void
    {
        if (empty($tabId)) {
            $tabId = array_key_first($this->structConfig['list']['tabs'] ?? []) ?? 'default';
        }
        $repo = $this->getRepository($tabId);
        $metadata = $repo->getFieldMetadata();

        foreach ($columns as $key => $value) {
            if ($value instanceof AbstractField) {
                $this->addListColumn($tabId, $value);
                continue;
            }
            $field = is_string($key) ? $key : (string) $value;
            $options = is_array($value) ? $value : [];
            $meta = $metadata[$field] ?? [];
            if (empty($options['label'])) $options['label'] = $meta['label'] ?? ucfirst($field);
            if (empty($options['type'])) $options['type'] = $meta['genericType'] ?? 'text';
            $options = array_merge($meta, $options);
            $this->addListColumn($tabId, $field, $options['label'], $options['type'], $options);
        }
    }

    protected function setEditFields(array $fields, string $sectionId = 'main'): void
    {
        foreach ($fields as $key => $value) {
            if ($value instanceof Panel) {
                $panelId = $value->getField();
                if (!isset($this->structConfig['edit']['sections'][$panelId])) {
                    $this->addEditSection($panelId, $value->getLabel());
                }
                $this->setEditFields($value->getFields(), $panelId);
                continue;
            }
            if ($value instanceof AbstractField) {
                $this->addEditField($sectionId, $value);
                continue;
            }
            $field = is_string($key) ? $key : $value;
            $options = is_array($value) ? $value : [];
            $this->addEditField($sectionId, $field, $options['label'] ?? ucfirst($field), $options['type'] ?? 'text', $options);
        }
    }

    // ── View Descriptor ───────────────────────────────────────────

    public function getViewDescriptor(): array
    {
        $t = $this->getTranslator();
        $descriptor = [
            'mode'     => $this->mode,
            'method'   => 'POST',
            'action'   => '?module=' . static::getModuleName() . '&controller=' . static::getControllerName(),
            'recordId' => $this->recordId,
            'record'   => [],
            'buttons'  => [],
            'body'     => null,
        ];

        foreach ($this->structConfig['edit']['head_buttons'] ?? [] as $btn) {
            $label = $btn['label'] ?? '';
            $icon = $btn['icon'] ?? '';
            $type = $btn['type'] ?? 'secondary';

            if (($btn['name'] ?? '') === 'save') {
                if (empty($this->recordId) || $this->recordId === 'new') {
                    $label = $t->translate('create');
                    $icon = 'fas fa-plus';
                    $type = 'success';
                }
            }

            $descriptor['buttons'][] = [
                'label' => $label, 'icon' => $icon, 'type' => $type,
                'action' => $btn['action'] ?? 'submit', 'target' => $btn['target'] ?? '', 'name' => $btn['name'] ?? '',
            ];
        }

        // Build body from sections
        if ($this->useTabs) {
            $tabs = $this->getTabs();
            if (!empty($tabs)) {
                $descriptor['body'] = new Panel('', [new TabGroup($tabs, ['id' => 'edit-tabs'])], ['col' => 'col-12']);
            }
        } else {
            $panels = [];
            foreach ($this->structConfig['edit']['sections'] ?? [] as $secId => $section) {
                $panels[] = new Panel($section['title'] ?? ucfirst($secId), $section['fields'] ?? [], ['col' => $section['col'] ?? 'col-md-6']);
            }
            if (count($panels) === 1) {
                $descriptor['body'] = $panels[0];
            } elseif (count($panels) > 1) {
                $descriptor['body'] = new Panel('', $panels, ['col' => 'col-12']);
            }
        }

        // Record data
        if ($this->mode === ResourceInterface::MODE_EDIT && $this->recordId && $this->recordId !== 'new') {
            $data = $this->fetchRecordData();
            $descriptor['record'] = $data['data'] ?? [];
        }

        return $descriptor;
    }

    public function getActiveTab(): string
    {
 return $this->activeTab; 
}
    public function getFields(string $mode = 'edit'): array
    {
 return $this->structConfig[$mode]['sections'] ?? []; 
}

    // ── Internal Helpers ──────────────────────────────────────────

    private function collectFieldDefinitions(): array
    {
        $fieldDefs = [];
        $collect = function ($fields) use (&$fieldDefs, &$collect) {
            foreach ($fields as $f) {
                if ($f instanceof Panel) {
                    $collect($f->getFields());
                } elseif ($f instanceof AbstractField) {
                    $fieldDefs[$f->getField()] = $f;
                }
            }
        };
        foreach ($this->structConfig['edit']['sections'] ?? [] as $section) {
            if (!empty($section['fields'])) $collect($section['fields']);
        }
        return $fieldDefs;
    }

    protected function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }

    protected function jsonResponse(array $data): void
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
