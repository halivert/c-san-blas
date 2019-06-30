@extends('layouts.layout')

@section('navbar')
  @include('includes.navbar')
@endsection

@section('body')
  <template v-once>
    @{{ (bulk = @json($markedHouses)) ? '' : '' }}
  </template>
  <div class="section">
    <div class="columns is-centered">
      <div class="column is-8-desktop columns is-centered">
        <div class="column field">
          <label class="label">{{ __('labels.group') }}</label>
          <div class="control">
            <div class="select is-fullwidth">
              <select
                v-model="selectedGroup"
                @change="selectGroup">
                <option value="">{{ __('labels.all') }}</option>
                <option
                  v-for="(value, name, index) in markedHouses"
                  :key="index"
                  :value="name">
                {{ __('labels.group') }} @{{ name }}
                </option>
              </select>
            </div>
          </div>
        </div>

        <div class="column field">
          <label class="label">{{ __('labels.territory') }}</label>
          <div class="control">
            <div class="select is-fullwidth">
              <select
                v-model="selectedTerritory"
                @change="selectTerritory">
                <option value="">{{ __('labels.all') }}</option>
                <option
                  v-for="(value, name, index) in availableTerritories"
                  :key="index"
                  :value="name">
                {{ __('labels.territory') }} @{{ name }} @{{ value[0].territory_description }}
                </option>
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="columns is-centered">
      <div class="column is-8-desktop box" v-if="!selectedTerritory">
        <table class="table is-fullwidth is-narrow">
          <thead>
            <tr>
              <th>{{ __('labels.territory') }}</th>
              <th>
                <abbr title="{{ __('Block or private') }}">
                  {{ __('abbreviations.block') }}
                </abbr>
              </th>
              <th>{{ __('labels.houses') }}</th>
            </tr>
          </thead>
          <tbody>
            <template v-for="(array, territoryNumber, index) in actualGroup">
              <tr v-for="(row, nindex) in array" :key="territoryNumber + '.' + nindex">
                <td
                  v-if="nindex <= 0"
                  :rowspan="array.length"
                  class="has-text-vcentered">
                  @{{ row.territory_number }} @{{ row.territory_description }}
                </td>
                <td>@{{ row.block }}</td>
                <td>@{{ row.houses }}</td>
              </tr>
            </template>
          </tbody>
        </table>
      </div>
    </div>

    <div class="columns is-centered">
      <div class="column is-8-desktop box" v-if="selectedTerritory">
        <table class="table is-fullwidth">
          <thead>
            <tr>
              <th>{{ __('Block or private') }}</th>
              <th>{{ __('labels.houses') }}</th>
            </tr>
          </thead>
          <tbody>
            <template v-for="(value, index) in actualTerritory">
              <tr v-for="(nvalue, nindex) in value.houses.split(',')" :key="index + ' ' + nindex">
                <td
                  v-if="nindex <= 0"
                  :rowspan="value.houses.split(',').length"
                  class="has-text-vcentered">
                  @{{ value.territory_description }} @{{ value.block }}
                </td>
                <td>@{{ nvalue }}</td>
              </tr>
            </template>
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script src="{{ mix('js/marked-houses.js') }}" charset="utf-8"></script>
@endsection
