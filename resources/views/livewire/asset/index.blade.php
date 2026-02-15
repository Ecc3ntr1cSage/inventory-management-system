<div>
	<h2 class="text-lg font-semibold mb-4">Pending Applications (status = 0)</h2>

	@if($applications && $applications->count())
		<table class="min-w-full divide-y divide-gray-200">
			<thead class="bg-gray-50">
				<tr>
					<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
					<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applicant</th>
					<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asset</th>
					<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Application Date</th>
					<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
					<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
				</tr>
			</thead>
			<tbody class="bg-white divide-y divide-gray-200">
				@foreach($applications as $app)
					<tr>
						<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $app->id }}</td>
						<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ optional($app->user)->name ?? $app->guest_name }}</td>
						<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ optional($app->asset)->name ?? '—' }}</td>
						<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $app->application_date }}</td>
						<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ Str::limit($app->reason, 80) }}</td>
						<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Pending</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	@else
		<div class="text-sm text-gray-600">No pending applications found.</div>
	@endif
</div>

