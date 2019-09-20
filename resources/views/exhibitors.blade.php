@extends('layouts.layout')

@section('navbar')
	@include('includes.navbar')
@endsection

@section('body')
	@tovue(bulk, json_encode($publishers), false)

	<modal ref="confirmDeleteModal">
		<confirm-message
			class="is-danger"
			title="{{ __('labels.alert') }}"
			confirm-label="{{ __('labels.confirm') }}"
			cancel-label="{{ __('labels.cancel') }}"
			message="{{ __('This will delete all publishers assigned, are you sure?') }}"
			@confirm="deleteAssigned"
			@cancel="$refs['confirmDeleteModal'].hideModal()">
		</confirm-message>
	</modal>

	<div class="section">
		<div class="columns is-centered">
			<div class="column is-full-touch is-half-desktop">
				<form>
					<div class="field-body field">
						<div class="field">
							<div class="field">
								<div class="control">
									<label class="radio">
										<input
											type="radio"
											name="selectedFilter"
											v-model="selectedFilter"
											value="1">
										{{ __('labels.schedule') }}
									</label>
								</div>
							</div>

							<div class="field">
								<div class="control">
									<label class="radio">
										<input
											type="radio"
											name="selectedFilter"
											v-model="selectedFilter"
											value="2">
										{{ __('labels.day') }}
									</label>
								</div>
							</div>
						</div>

						<div class="field" v-if="selectedFilter === '1'">
							<label class="label">{{ __('labels.schedule') }}</label>
							<div class="control">
								<div class="select is-fullwidth">
									<select
										v-model="selectedSchedule"
										:autofocus="true">
										<option value="" disabled>{{ __('Select a schedule') }}</option>
										<option
											v-for="schedule in schedules"
											:value="schedule.value"
											:key="schedule.id">
										@{{ schedule.value }}
										</option>
									</select>
								</div>
								<br />
							</div>
						</div>

						<div class="field" v-else-if="selectedFilter === '2'">
							<label class="label">{{ __('labels.day') }}</label>
							<div class="control">
								<div class="select is-fullwidth">
									<select
										v-model="selectedDay"
										:autofocus="true">
										<option value="" disabled>{{ __('Select a day') }}</option>
										<option v-for="day in days" :value="day.id" :key="day.id">
										@{{ day.value }}
										</option>
									</select>
								</div>
								<br />
							</div>
						</div>
					</div>

					<div class="columns is-mobile is-multiline">
						<div class="column has-text-centered is-size-6">
							{{ __('labels.assigned') }}: <strong>@{{ publishersAssigned.length }}</strong>
						</div>
						<div class="column has-text-centered is-size-6">
							{{ __('labels.availables') }}: <strong>@{{ publishersFiltered.length }}</strong>
						</div>
					</div>

					<div class="field is-grouped is-grouped-centered">
						<div class="control">
							<button
								class="button is-success"
								type="button"
								@click="showAssigned">
								{{ __('Show assigned') }}
							</button>
						</div>

						<div class="control">
							<button
								class="button is-danger"
								type="button"
								@click="confirmDeleteAssigned">
								{{ __('labels.delete') }}
							</button>
						</div>
					</div>
				</form>

				<div class="modal" :class="{ 'is-active': modalShown }">
					<div class="modal-background" @click="hideModal"></div>
					<section class="modal-content scroll-x">
						<div class="box">
							<h3 class="title is-4 has-text-centered">Asignados</h3>
							<table class="table is-fullwidth is-hoverable is-narrow">
								<thead>
									<tr>
										<th>{{ __('labels.name') }}</th>
										<th>{{ __('Day and hour') }}</th>
										<th>{{ __('labels.phone') }}</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<tr
										v-for="publisher in publishersAssigned"
										:key="publisher.id">
										<td class="has-text-vcentered">@{{ publisher.name }}</td>
										<td class="has-text-vcentered">
											@{{ publisher.assigned.value }}:
											@{{ publisher.activeSchedule }}
										</td>
										<td class="has-text-vcentered"
												@mousedown.stop
												@dblclick.stop
												@touchstart.stop
												class="has-text-vcentered">
											<button
												@click="callPublisher(publisher.phone)"
												:class="[ { 'is-purple': publisher.phone }, 'button',
																'is-fullwidth', 'is-outlined' ]"
												:disabled="!publisher.phone"
												type="submit">
												<i class="fas fa-phone-alt" v-if="publisher.phone"></i>
											</button>
										</td>
										<td class="has-text-vcentered">
											<button
												@click="removePublisher(publisher)"
												class="delete is-medium">
											</button>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</section>
					<button
						class="modal-close is-large"
						aria-label="close"
						@click="hideModal">
					</button>
				</div>
			</div>
		</div>

		<div class="columns is-centered">
			<div class="column is-full-touch is-half-desktop">
				<div class="box">
					<table class="table is-fullwidth is-narrow is-hoverable">
						<thead>
							<tr>
								<th>{{ __('labels.name') }}</th>
								<th v-if="selectedFilter === '1'">{{ __('Available days') }}</th>
								<th v-else-if="selectedFilter === '2'">{{ __('Available schedules') }}</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="publisher in publishersFiltered" :key="publisher.id">
								<td class="has-text-vcentered">@{{ publisher.name }}</td>
								<td class="has-text-vcentered" v-if="selectedFilter === '1'">
									<div
										@mousedown.stop
										@dblclick.stop
										@touchstart.stop
										class="dropdown is-up is-right is-fullwidth is-vcentered">
										<div class="dropdown-trigger is-fullwidth">
											<button
												class="button is-primary is-fullwidth"
												@click.stop="showDropdownSchedule($event, publisher)"
												aria-haspopup="true">
												<template
													v-for="(day, index) in publisher.days[selectedSchedule]">
													@{{ day }}
													<br v-if="index < publisher.days[selectedSchedule].length - 1">
												</template>
											</button>
										</div>
										<div
											class="dropdown-menu">
											<div class="dropdown-content">
												<a
													v-for="(day, index) in publisher.days[selectedSchedule]"
													:key="index"
													@click="selectPublisherSchedule(publisher, day)"
													class="dropdown-item">
													@{{ day }}
												</a>
											</div>
										</div>
									</div>
								</td>

								<td class="has-text-vcentered" v-if="selectedFilter === '2'">
									<div
										@mousedown.stop
										@dblclick.stop
										@touchstart.stop
										class="dropdown is-up is-right is-fullwidth is-vcentered">
										<div class="dropdown-trigger is-fullwidth">
											<button
												class="button is-primary is-fullwidth"
												@click.stop="showDropdownDay($event, publisher)"
												aria-haspopup="true">
												<template v-for="(schedule, index) in
																				 publisher.schedules[selectedDay]">
													@{{ schedule }}
													<br v-if="index < publisher.schedules[selectedDay].length - 1">
												</template>
											</button>
										</div>
										<div
											class="dropdown-menu">
											<div class="dropdown-content">
												<a
													v-for="(schedule, index) in
																 publisher.schedules[selectedDay]"
													:key="index"
													@click="selectPublisherDay(publisher, schedule)"
													class="dropdown-item">
													@{{ schedule }}
												</a>
											</div>
										</div>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('scripts')
	<script src="{{ mix('js/exhibitors.js') }}" charset="utf-8"></script>
	<script src="{{ asset('js/all.min.js') }}"></script>
@endsection
