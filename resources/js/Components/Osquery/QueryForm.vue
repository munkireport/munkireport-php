<script lang="ts" setup>
import {ref, defineProps} from 'vue'
import {Link, usePage, useForm} from '@inertiajs/vue3'
import {useTranslation} from 'i18next-vue'
import CodeMirror from 'vue-codemirror6';
import { bracketMatching, syntaxHighlighting } from '@codemirror/language';
import { autocompletion, closeBrackets } from '@codemirror/autocomplete';
import { oneDarkTheme, oneDark, oneDarkHighlightStyle } from '@codemirror/theme-one-dark';
// import { Bootstrap } from 'codemirror6-bootstrap-theme/src/index.ts';
// import { Bootstrap, bootstrapHighlightStyle } from '@/codemirror6-bootstrap-theme'
import { githubLight, githubDark } from '@uiw/codemirror-theme-github';

import {sql} from '@codemirror/lang-sql'

const { t, i18next } = useTranslation();

export interface Events {
  (event: 'submitted'): void
}

export interface FormData {
  enabled: boolean;
  query: string;
  schedule_id: number;
  snapshot: boolean;

  denylist: boolean;
  is_discovery: boolean;

  result_key?: string;
}

const form = useForm<FormData>({
  enabled: true,
  frequency: 3600,
  denylist: true,
})
const emit = defineEmits<Events>()

</script>

<template>
  <form @submit.prevent="emit('submitted')">
    <div class="form-group">
      <label for="result-key">Identifier</label>
      <input class="form-control" id="result-key" type="text" v-model="form.result_key">
      <small id="identifierHelp" class="form-text text-muted">
        Only alphanumeric and underscore characters accepted. No whitespace
      </small>
    </div>
    <div class="form-group">
      <label for="query">Query</label>
      <code-mirror
        id="query"
        class="border"
        v-model="form.query"
        tab
        gutter
        :extensions="[autocompletion(), githubLight, sql()]"
      />
    </div>

    <div class="form-group">
      <input class="form-check-input" type="checkbox" value="1" id="enabled" v-model="form.enabled">
      <label class="form-check-label" for="enabled">{{ $t('enabled') }}</label>
      <small id="enabledHelp" class="form-text text-muted">
        When not enabled, no clients will attempt to perform this query.
      </small>
    </div>

    <div class="form-group">
      <label for="interval">Frequency</label>
      <div class="form-check">
        <input class="form-check-input" type="radio" name="interval" id="intervalDaily" value="daily" v-model="form.interval">
        <label class="form-check-label" for="intervalDaily">Daily</label>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="radio" name="interval" id="intervalHourly" value="3600" v-model="form.interval">
        <label class="form-check-label" for="intervalHourly">Hourly</label>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="radio" name="interval" id="intervalCustom" value="0">
        <label class="form-check-label" for="intervalCustom">Custom</label>
        <input type="text" class="form-control" placeholder="Number of seconds between queries">
      </div>
    </div>

    <div class="py-2 my-5">
      <h5>Restrictions</h5>
      <p>These settings restrict where your query can run</p>

      <div class="form-group py-4">
        <label for="platform">Restrict platform</label>
        <select class="form-control" id="platform">
          <option value="">Unrestricted (All Platforms)</option>
          <option value="darwin">macOS</option>
          <option value="linux">Linux</option>
          <option value="posix">Posix (macOS and Linux)</option>
          <option value="windows">Windows</option>
        </select>
        <small id="platformHelp" class="form-text text-muted">
          Selecting a platform here will restrict the query to that platform only.
        </small>
      </div>

      <div class="form-row">
        <div class="col-4">
          <div class="form-group">
            <label for="version">Minimum OSQuery Version</label>
            <input class="form-control" id="version" type="text">
            <small id="versionHelp" class="form-text text-muted">
              If using a table only available in some versions, specify the minimum OSQuery version here.
            </small>
          </div>
        </div>
      </div>

      <div class="form-group">
        <input class="form-check-input" type="checkbox" value="1" id="denylist" v-model="form.denylist">
        <label class="form-check-label" for="denylist">May be denylisted</label>
        <small id="denylistHelp" class="form-text text-muted">
          If this query runs for too long or consumes too many resources, the client may denylist it to ensure performance
        </small>
      </div>
    </div>
  </form>
</template>

