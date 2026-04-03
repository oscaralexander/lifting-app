@props([
    'confirmDelete' => __('document.confirm_delete'),
    'documents' => collect(),
])

<ol class="sortableList js-sortableList" data-sortable-list-event="sorted-documents">
    @foreach ($documents as $document)
        <x-admin.documents.document
            :confirmDelete="$confirmDelete"
            :document="$document"
        />
    @endforeach
</ol>