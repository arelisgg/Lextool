Vue.component('lex-article-element', {
    template:
        `
        <li class="lex_element" @click="updateElement(element.lex_article_element.id_lex_article_element)">
              <span style="margin-left: 3px" v-if="element.submodel_separator !== undefined && isFirstElement">{{ element.submodel_separator.representation }}</span>
            
              <span v-if="hasVisibility" class="elem" :style="elementStyles">
                {{ element.lex_article_element.element }}
                  <span v-if="element.element_separator !== undefined && element.element_separator !== null" class="sep">{{ element.element_separator.representation }}</span>
              </span>
              <span class="sub" v-if="subelementHasVisibility" :style="subElementStyles"> 
                 {{ element.sub_element_type }}
                 <span v-if="element.element_separator !== undefined && element.element_separator !== null" class="sep">{{ element.element_separator.representation }}</span>
              </span>
              <span v-if="element.type !== 'lemma'" class="elem-remover fa fa-remove" @click.self="deleteElement(element.lex_article_element.id_lex_article_element)">
              </span>
         </li>
         `,
    props: {
        element: {
            type: Object,
            required: true
        },
        lex_article_elements: {
            type: Array,
            required: true
        },
        sub_models_plan: {
            type: Array,
            required: true
        }
    },
    computed: {
        subelementHasVisibility() {
            if (this.element.sub_element !== undefined) {
                return !!this.element.sub_element.visibility;
            }else {
                return false
            }
        },
        hasVisibility() {
            if (this.element.element !== undefined) {
                return !!this.element.element.visibility;
            }else {
                return false
            }
        },
        elementStyles: function() {
            let styles = {
                color: '#'+this.element.element.color,
                fontFamily: this.element.element.font,
                fontSize: this.element.element.font_size,
                backgroundColor: '#'+this.element.element.background,
                fontWeight:  this.element.element.font_weight,
                textDecoration: this.element.element.text_decoration,
                textTransform: this.element.element.text_transform,
            }

            return styles
        },
        subElementStyles: function () {
            let styles = {
                color: '#'+this.element.sub_element.color,
                fontFamily: this.element.sub_element.font,
                fontSize: this.element.sub_element.font_size,
                backgroundColor: '#'+this.element.sub_element.background,
                fontWeight:  this.element.sub_element.font_weight,
                textDecoration: this.element.sub_element.text_decoration,
                textTransform: this.element.sub_element.text_transform,
            }

            return styles
        },
        isFirstElement: function () {
            if (this.element.submodel_separator !== undefined)  {
                let first_element = _.first(this.lex_article_elements)
                let first_sub_model_element = _.first(this.element.sub_model_active.elements)

                if (first_element === this.element
                    && this.element.submodel_separator !== ''
                    && (this.element.element.id_element !== first_sub_model_element.id_element
                        && this.element.sub_model.id_sub_model !== this.element.sub_model_active.id_sub_model )) {
                    return false
                }else if (first_element !== this.element
                    && this.element.submodel_separator !== ''
                    && (this.element.element.id_element === first_sub_model_element.id_element
                        && this.element.sub_model.id_sub_model === this.element.sub_model_active.id_sub_model )) {
                    return true
                }
            }
        }
    },
    methods: {
        updateElement: function (id_lex_article_element) {
            let lex_article_elements_arr = this.lex_article_elements

            let result

            for (let key in lex_article_elements_arr) {
                if (lex_article_elements_arr[key].lex_article_element.id_lex_article_element === id_lex_article_element) {
                    result = lex_article_elements_arr[key]
                    this.$emit('update', result)
                    break
                }
            }
        },
        subModelWasAssigned: function (sub_model) {
            for (let key in this.sub_models_plan) {
                if (sub_model.id_sub_model === this.sub_models_plan[key].id_sub_model) {
                    return true
                }
            }

            return false
        },
        deleteElement: function (id) {
            let lex_article_elements_arr = this.lex_article_elements

            let result = []

            for (let key in lex_article_elements_arr) {
                if (lex_article_elements_arr[key].lex_article_element.id_lex_article_element === id) {

                    if (this.subModelWasAssigned(lex_article_elements_arr[key].sub_model)) {
                        result.push(lex_article_elements_arr[key])
                        lex_article_elements_arr = _.filter(lex_article_elements_arr, function (item) {
                            return item !== lex_article_elements_arr[key]
                        })
                        result.push(lex_article_elements_arr)
                        this.$emit('delete', result)
                        break
                    }else {
                        krajeeDialogError.alert("El elemento seleccionado no está asignado a su plan de redacción");
                        this.$emit('unselect')
                        break
                    }
                }
            }
        }
    }
});







