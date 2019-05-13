Vue.component('modal-lex-article-element', {
    template:
        `
        <a href="#" class="list-group-item" @click.prevent="changeModalLexArtElemActive">
        <span class="prev"  v-if="element.prev !== ''">
         <span class="elem" v-if="elementPrevHasVisibility" :style="prevElementStyles">
                {{ element.prev.lex_article_element.element }}
                  <span v-if="element.prev.lex_article_element.element !== ''" class="sep">{{ element.prev.element_separator.representation }}</span>
              </span>
              <span class="sub" v-if="subelementPrevHasVisibility" :style="prevSubElementStyles"> 
                 {{ element.prev.sub_element_type }}
                 <span class="sep">{{ element.prev.element_separator.representation }}</span>
         </span>  
       </span>
        <span class="next" v-else-if="element.prev === ''">( Insertar Aquí )</span> 
        
        <span v-if="element.prev !== '' && element.next !== ''"> ( Insertar Aquí ) </span> 
        
         <span class="next" v-if="element.next !== ''">
           <span class="elem" v-if="elementNextHasVisibility" :style="nextElementStyles">
                {{ element.next.lex_article_element.element }}
                  <span v-if="element.next.lex_article_element.element !== ''" class="sep">{{ element.next.element_separator.representation }}</span>
              </span>
              <span class="sub" v-if="subelementNextHasVisibility" :style="nextSubElementStyles"> 
                 {{ element.next.sub_element_type }}
                 <span class="sep">{{ element.next.element_separator.representation }}</span>
              </span> 
           </span>
         </span>
         <span class="next" v-else-if="element.next === ''">( Insertar Aquí )</span>           
        </a>
        `,
    props: {
        element: {
            type: Object,
            required: true
        }
    },
    computed: {
        subelementPrevHasVisibility() {
            if (this.element.prev.sub_element !== undefined) {
                if (this.element.prev.sub_element.visibility)
                    return true
                else {
                    return false
                }
            }else {
                return false
            }
        },
        elementPrevHasVisibility() {
            if (this.element.prev.element !== undefined) {
                if (this.element.prev.element.visibility)
                    return true
                else {
                    return false
                }

            }else {
                return false
            }
        },
        subelementNextHasVisibility() {
            if (this.element.next.sub_element !== undefined) {
                if (this.element.next.sub_element.visibility)
                    return true
                else {
                    return false
                }
            }else {
                return false
            }
        },
        elementNextHasVisibility() {
            if (this.element.next.element !== undefined) {
                if (this.element.next.element.visibility)
                    return true
                else {
                    return false
                }
            }else {
                return false
            }
        },
        prevElementStyles: function() {
            let styles = {
                color: '#'+this.element.active ?  'fff' : this.element.prev.element.color,
                fontFamily: this.element.prev.element.font,
                fontSize: this.element.prev.element.font_size,
                // backgroundColor: '#'+this.element.prev.element.background,
                fontWeight:  this.element.prev.element.font_weight,
                textDecoration: this.element.prev.element.text_decoration,
                textTransform: this.element.prev.element.text_transform,
                cursor: 'pointer'
            }

            return styles
        },
        prevSubElementStyles: function () {
            let styles = {
                color: '#'+this.element.active ?  'fff' : this.element.prev.sub_element.color,
                fontFamily: this.element.prev.sub_element.font,
                fontSize: this.element.prev.sub_element.font_size,
                //backgroundColor: '#'+this.element.prev.sub_element.background,
                fontWeight:  this.element.prev.sub_element.font_weight,
                textDecoration: this.element.prev.sub_element.text_decoration,
                textTransform: this.element.prev.sub_element.text_transform,
                cursor: 'pointer'
            }

            return styles
        },
        nextElementStyles: function() {
            let styles = {
                color: '#'+this.element.active ?  'fff' : this.element.next.sub_element.color,
                fontFamily: this.element.next.element.font,
                fontSize: this.element.next.element.font_size,
                //backgroundColor: '#'+this.element.next.element.background,
                fontWeight:  this.element.next.element.font_weight,
                textDecoration: this.element.next.element.text_decoration,
                textTransform: this.element.next.element.text_transform,
                cursor: 'pointer'
            }

            return styles
        },
        nextSubElementStyles: function () {
            let styles = {
                color: '#'+this.element.active ?  'fff' : this.element.next.sub_element.color,
                fontFamily: this.element.next.sub_element.font,
                fontSize: this.element.next.sub_element.font_size,
                // backgroundColor: '#'+this.element.next.sub_element.background,
                fontWeight:  this.element.next.sub_element.font_weight,
                textDecoration: this.element.next.sub_element.text_decoration,
                textTransform: this.element.next.sub_element.text_transform,
                cursor: 'pointer'
            }

            return styles
        },
    },
    methods: {
        changeModalLexArtElemActive(){
            this.$emit('change-modal-element', this.element)
        }
    }

})