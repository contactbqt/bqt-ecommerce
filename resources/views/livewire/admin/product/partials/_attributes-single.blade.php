<div class="max-w-md">
    <flux:select wire:model="attributeSelections.{{ $item->attribute->id }}" placeholder="Select {{ $item->attribute->attribute_name }}">
        <flux:select.option value="">None</flux:select.option>
        @foreach($categoryAttributeValues as $valueItem)
            <flux:select.option value="{{ $valueItem->id }}">{{ $valueItem->value_name }}</flux:select.option>
        @endforeach
    </flux:select>
</div>
