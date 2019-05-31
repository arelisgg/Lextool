<?php

use antkaz\vue\VueAsset;
VueAsset::register($this); // register VueAsset
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\LexArticle */

$this->title = 'Editar artículo lexicográfico';
$this->params['breadcrumbs'][] = ['label' => 'Planes de redacción de artículos lexicográficos' , 'url' => ['art_red_task/plans','id_project' => $project->id_project]];
$this->params['breadcrumbs'][] = ['label' => 'Redacción de artículos lexicográficos' , 'url' => ['art_red_task/index','id_redaction_plan' => $plan->id_redaction_plan]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="id_project" class="hidden"><?= $project->id_project?></div>
<div id="name_project" class="hidden"><?= $project->name?></div>
<div id="redaction_plan" class="hidden"><?= $plan->id_redaction_plan?></div>
<div id="lex_article" class="hidden"><?= $lex_article->id_lex_article?></div>
<div class="lex-article-update">
    <div id="app" class="vue">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-success box-shadow">
                    <div class="box-header with-border">
                        <h2 class="box-title">Artículo lexicográfico: <?= $lex_article->lemma->extracted_lemma?></h2>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="box box-solid box-shadow">
                                            <div class="box-header with-border">
                                                <h5 class="box-title" ><i class="fa fa-pencil"></i> Autocompletar modelo de artículo</h5>
                                            </div>
                                            <div class="box-body">
                                                <ul id="elements" class="submodel-container">
                                                    <li
                                                            :id="elem.id_element"
                                                            :class="{ selected: elem.selected, lemma: elem.type === 'Lema', inPlan: inPlan(elem), next_to_write: elem.next_to_write }"
                                                            class="sub_model_element" v-if="elem.id_element !== undefined"
                                                            v-for="(elem, key , index) in filterModel"
                                                            @click.prevent="loadElaborationArea(elem)">
                                                        <span class="sub_model_element"><span v-if="elem.next_to_write"><i class="fa fa-arrow-right"></i></span> {{ elem.type }}
                                                         <span v-if="modelRequired(elem) || modelRepeated(elem)">( <span v-if="modelRequired(elem)"><i class="fa fa-check"></i></span> <span v-if="modelRepeated(elem)"><i class="fa fa-refresh"></i></span> )</span>
                                                        </span>
                                                        <span :id="elem.separator.id_separator" :class="{ current: elem.separator.selected }">{{ elem.separator.representation }}</span>
                                                    </li>
                                                    <li :id="elem.id_sub_model" class="sub_model" :class="{ active: isActiveSubModel(elem) }" v-else-if="elem.id_sub_model !== undefined" :class="{ repeat: elem.repeat, required: elem.required }" style="display: none"></li>
                                                    <li :id="elem.id_separator" class="sub_model_separator" v-else-if="elem.id_separator !== undefined">
                                                        {{ elem.representation }}
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <!--Lema de lemario -->
                                        <div v-if="type === 'lemma'" class="box box-primary box-shadow">
                                            <div class="box-header with-border">
                                                <h2 class="box-title"><i class="fa fa-pencil"></i> Elaboración lema del lemario</h2>
                                            </div>
                                            <div class="box-body">
                                                <form @submit.prevent="onSubmit(element.index)">
                                                    <input type="hidden" name="_csrf-backend" value="sfZV2JPu9gsYrP390yKjq61gGeHWTYKZF7XkIcWp4FbEoDjhyY-3bX7zq7KUU5blgCZ_tKUK8Mt8goVUps6Wbg==">

                                                    <div class="col-md-2">
                                                        <label>{{ element.type }}: </label>
                                                        <p>{{ element.lemma }}</p>
                                                        <input type="hidden" :value="element.lemma" id="lemma">
                                                        <input type="hidden" :value="element.id_element" id="id_element">
                                                    </div>
                                                    <div class="col-md-10">
                                                        <label for="sub_element">Subelementos: </label>
                                                        <select id="sub_element" class="form-control" name="sub_element" title="Sub-Tipo" v-model="create_element_data.sub_type">
                                                            <option  value=""></option>
                                                            <option :selected="create_element_data.sub_type === sub_element.id_sub_element" :value="sub_element.id_sub_element" v-for="sub_element in element.sub_elements">
                                                                {{ sub_element.name }}
                                                            </option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-12 margin-top-20">
                                                        <button id="description_form_button" type="submit" class="btn btn-success">Guardar</button>
                                                    </div>

                                                </form>
                                            </div>
                                        </div>

                                        <!--Lema de referencia -->
                                        <div v-if="type === 'reference_lemma'" class="box box-primary box-shadow">
                                            <div class="box-header with-border">
                                                <h2 class="box-title"><i class="fa fa-pencil"></i> Elaboración lema de referencia</h2>
                                            </div>
                                            <div class="box-body">
                                                <form @submit.prevent="onSubmit(element)">
                                                    <input type="hidden" name="_csrf-backend" value="sfZV2JPu9gsYrP390yKjq61gGeHWTYKZF7XkIcWp4FbEoDjhyY-3bX7zq7KUU5blgCZ_tKUK8Mt8goVUps6Wbg==">

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label>Lemas del Lemario: </label>
                                                            <autocomplete
                                                                    @empty="empty_autocomplete = $event"
                                                                    :empty_autocomplete="empty_autocomplete"
                                                                    @text="create_element_data.text = $event"
                                                                    :text="create_element_data.text"
                                                                    :items="lemario_list">
                                                            </autocomplete>
                                                        </div>
                                                    </div>

                                                    <div class="row margin-top-20">
                                                        <div  class="col-md-12">
                                                            <label for="sub_element">Subelementos: </label>
                                                            <select id="sub_element" class="form-control" name="sub_element" title="Sub-Tipo" v-model="create_element_data.sub_type">
                                                                <option value=""></option>
                                                                <option :selected="create_element_data.sub_type === sub_element.id_sub_element" :value="sub_element.id_sub_element" v-for="sub_element in element.sub_elements">
                                                                    {{ sub_element.name }}
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12 margin-top-20">
                                                        <button id="description_form_button" type="submit" class="btn btn-success">Guardar</button>
                                                    </div>

                                                </form>
                                            </div>
                                        </div>

                                        <!--Descripcion-->
                                        <div v-if="type === 'desc'" class="box box-primary box-shadow">
                                            <div class="box-header with-border">
                                                <h2 v-if="!repeat" class="box-title"><i class="fa fa-pencil"></i> Elaboración componente</h2>
                                                <h2 v-else class="box-title"><i class="fa fa-pencil"></i> Elaboración componente repetitivo</h2>
                                            </div>
                                            <div class="box-body">
                                                <form @submit.prevent="onSubmit(element)">
                                                    <input type="hidden" name="_csrf-backend" value="sfZV2JPu9gsYrP390yKjq61gGeHWTYKZF7XkIcWp4FbEoDjhyY-3bX7zq7KUU5blgCZ_tKUK8Mt8goVUps6Wbg==">

                                                    <div class="col-md-12">
                                                        <label for="sub_element">Subelementos: </label>
                                                        <select id="sub_element" class="form-control" name="sub_element" title="Sub-Tipo" v-model="create_element_data.sub_type">
                                                            <option value=""></option>
                                                            <option :selected="create_element_data.sub_type === sub_element.id_sub_element" :value="sub_element.id_sub_element" v-for="sub_element in element.sub_elements">
                                                                {{ sub_element.name }}
                                                            </option>
                                                        </select>
                                                    </div>


                                                    <div class="col-md-12 margin-top-20">
                                                        <button id="description_form_button" type="submit" class="btn btn-success">Guardar</button>
                                                    </div>

                                                </form>
                                            </div>
                                        </div>

                                        <!--Redaccion-->
                                        <div v-else-if="type === 'red'" class="box box-primary box-shadow">
                                            <div class="box-header with-border">
                                                <h2 v-if="!repeat" class="box-title"><i class="fa fa-pencil"></i> Elaboración componente</h2>
                                                <h2 v-else class="box-title"><i class="fa fa-pencil"></i> Elaboración componente repetitivo</h2>
                                            </div>
                                            <div class="box-body">
                                                <form @submit.prevent="onSubmit(element)">
                                                    <input type="hidden" name="_csrf-backend" value="sfZV2JPu9gsYrP390yKjq61gGeHWTYKZF7XkIcWp4FbEoDjhyY-3bX7zq7KUU5blgCZ_tKUK8Mt8goVUps6Wbg==">

                                                    <div class="col-md-12" v-show="element.type !== 'Acepción'">
                                                        <label for="sub_element">Sub-Tipos: </label>
                                                        <select id="sub_element" class="form-control" name="sub_element" title="Sub-Tipo" v-model="create_element_data.sub_type">
                                                            <option value=""></option>
                                                            <option :selected="create_element_data.sub_type === sub_element.id_sub_element" :value="sub_element.id_sub_element" v-for="sub_element in element.sub_elements">
                                                                {{ sub_element.name }}
                                                            </option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-12 margin-top-20">
                                                        <label for="article">Redacción</label>
                                                        <input v-if="element.type === 'División Silábica' || element.type === 'División silábica'" id="article" class="form-control" v-model="create_element_data.text" :placeholder="create_element_data.text">
                                                        <input v-else-if="element.type === 'Acepción'" id="article" class="form-control" v-model="create_element_data.text" :placeholder="create_element_data.text">
                                                        <textarea v-else id="article" class="form-control" v-model="create_element_data.text" :placeholder="create_element_data.text" rows="5"></textarea>
                                                    </div>

                                                    <div class="col-md-12 margin-top-20">
                                                        <button id="description_form_button" type="submit" class="btn btn-success">Guardar</button>
                                                    </div>

                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="box box-solid box-shadow">
                                            <div class="box-header with-border">
                                                <h5 style="font-weight: bolder;"><i class="fa fa-book"></i> Artículo lexicográfico resultante</h5>
                                            </div>
                                            <div class="box-body">
                                                <div class="article">
                                                    <ul id="lexicographical_element">
                                                        <lex-article-element
                                                                :key="element.lex_article_element.id_lex_article_element"
                                                                v-if="element.element !== undefined"
                                                                :lex_article_elements="lex_article_elements"
                                                                :element="element"
                                                                :sub_models_plan="sub_models_plan"
                                                                v-for="(element, index) in lex_article_elements"
                                                                @delete="deleteLexArtElem($event)"
                                                                @unselect="unSelectElements()"
                                                                @update="updateLexArtElem($event)">
                                                        </lex-article-element>
                                                    </ul>
                                                    <form id="save-article" @submit.prevent="saveLexArticle" method="post" action="save-lex-article">
                                                        <input type="hidden" name="_csrf-backend" value="sfZV2JPu9gsYrP390yKjq61gGeHWTYKZF7XkIcWp4FbEoDjhyY-3bX7zq7KUU5blgCZ_tKUK8Mt8goVUps6Wbg==">
                                                        <input type="hidden" id="final_article" name="article" value="">
                                                        <input type="hidden" name="lex_article_id" :value="lex_article.id_lex_article">
                                                        <input type="hidden" name="id_redaction_plan" :value="id_redaction_plan">
                                                        <input type="hidden" name="lemma_id" :value="current_lemma.id_lemma">
                                                        <div class="checkbox margin-top-10">
                                                            <label for="finished">
                                                                <input id="finished" type="checkbox" :value="lex_article.finished" v-model="lex_article.finished" name="finished"> {{ finished_text }}
                                                            </label>
                                                        </div>
                                                        <button class="btn btn-primary margin-top-10">Editar artículo lexicográfico</button>
                                                    </form>

                                                    <div id="interval-list" class="fade modal" role="dialog" tabindex="-1" style="display: none;">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                                    <h3>Seleccione en qué intervalo de elementos desea realizar la inserción</h3>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="list-group">
                                                                        <modal-lex-article-element
                                                                                :class="{ active: element.active }"
                                                                                @change-modal-element="changeModalLexArtElemActive($event)"
                                                                                :element="element"
                                                                                :key="element.index"
                                                                                v-for="(element,key,index) in lex_article_elements_interval">
                                                                        </modal-lex-article-element>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button @click="insertLexArtElem(current_lex_article_element.prev,current_lex_article_element.data,current_lex_article_element.next)" data-dismiss="modal" class="btn btn-success">Guardar</button>
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="box box-solid box-shadow">
                                    <div class="box-header with-border">
                                        <h5 class="text-center" style="font-weight: bolder;"><i class="fa fa-book"></i> Listado de diccionarios</h5>
                                    </div>
                                    <div class="box-body">
                                        <ul class="block__list block__list_tags" style="margin: 0px; padding: 0px">
                                            <li v-for="dictionary in dictionaries" class="text-center" style="padding: 10px; width: 100%">
                                                <a href="#" @click.prevent="loadDictionary(dictionary.name, dictionary.link)" ><i class="fa fa-bookmark"></i> {{ dictionary.name }}</a>
                                            </li>
                                        </ul>

                                        <div id="dictionary" class="fade modal" role="dialog" tabindex="-1" style="display: none;">
                                            <div class="modal-dialog modal-full">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                        <h2 id="dictionary-title">{{ dictionary.name }}</h2>
                                                    </div>
                                                    <div class="modal-body">
                                                        <iframe :src="dictionary.link" width="100%" height="100%"></iframe>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>