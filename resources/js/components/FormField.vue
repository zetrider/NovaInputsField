<template>
  <default-field :field="field" :errors="errors" :show-help-text="showHelpText">
    <template slot="field">
      <div
        class="nova-inputs-fields__container py-3 mb-3"
        v-for="(val, valueIndex) in value"
        :key="valueIndex"
      >
        <button class="mr-2" type="button" @click="deleteInput(valueIndex)">
          &times;
        </button>
        <div class="border border-40 rounded p-6 pb-3 w-full">
          <div
            class="mb-3"
            v-for="(template, templateKey) in field.templates"
            :key="templateKey"
          >
            <div v-if="template.type === 'input'">
              <input
                class="w-full form-control form-input form-input-bordered"
                v-bind="inputAttributes(template)"
                v-model="value[valueIndex][templateKey]"
              />
            </div>

            <div v-else-if="template.type === 'select'">
              <select
                class="w-full form-control form-input form-input-bordered"
                v-bind="inputAttributes(template)"
                v-model="value[valueIndex][templateKey]"
              >
                <option value="" disabled="">
                  {{ template.attributes["placeholder"] || "---" }}
                </option>
                <option
                  v-for="(option, optionIndex) in template.options"
                  :value="optionIndex"
                  :key="optionIndex"
                >
                  {{ option }}
                </option>
              </select>
            </div>

            <div v-else-if="template.type === 'radio'">
              <div class="text-80 mb-2" v-if="'placeholder' in template.attributes">{{ template.attributes["placeholder"] }}</div>
              <div
                v-for="(option, optionIndex) in template.options"
                :key="optionIndex"
              >
                <label>
                  <input
                    type="radio"
                    v-bind="inputAttributes(template)"
                    v-model="value[valueIndex][templateKey]"
                    :value="optionIndex"
                  />
                  {{ option }}
                </label>
              </div>
            </div>

            <div v-else-if="template.type === 'checkbox'">
              <div class="text-80 mb-2" v-if="'placeholder' in template.attributes">{{ template.attributes["placeholder"] }}</div>
              <div
                v-for="(option, optionIndex) in template.options"
                :key="optionIndex"
              >
                <label>
                  <input
                    type="checkbox"
                    v-bind="inputAttributes(template)"
                    v-model="value[valueIndex][templateKey]"
                    :value="optionIndex"
                  />
                  {{ option }}
                </label>
              </div>
            </div>
          </div>
        </div>
      </div>

      <button
        type="button"
        class="btn btn-default btn-primary"
        @click="addInput"
      >
        +
      </button>
    </template>
  </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from "laravel-nova";

export default {
  mixins: [FormField, HandlesValidationErrors],

  props: ["resourceName", "resourceId", "field"],

  methods: {
    /**
     * Set template
     */
    addInput: function () {
      let template = {};
      for (var key of Object.keys(this.field.templates)) {
        template[key] = this.field.templates[key].multiple ? [] : "";
      }
      this.value.push(template);
    },

    /**
     * Selete template
     */
    deleteInput: function (index) {
      this.value.splice(index, 1);
    },

    /**
     * Set attributes
     */
    inputAttributes(template) {
      return {
        ...template.attributes,
      };
    },

    /*
     * Set the initial, internal value for the field.
     */
    setInitialValue() {
      if (typeof this.field.value === "object" && this.field.value !== null) {
        this.value = this.field.value;
      } else {
        this.value = [];
      }
    },

    /**
     * Fill the given FormData object with the field's internal value.
     */
    fill(formData) {
      formData.append(this.field.attribute, JSON.stringify(this.value));
    },
  },
};
</script>

<style scoped>
.nova-inputs-fields__container {
  display: flex;
  align-items: flex-start;
}
</style>