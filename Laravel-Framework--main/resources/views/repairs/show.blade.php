<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">รายละเอียดงานซ่อม #{{ $repair->id }}</h2>
    </x-slot>

    <div class="py-12 max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white p-6 shadow-sm rounded-lg">
            <h3 class="text-2xl font-bold mb-2">{{ $repair->title }}</h3>
            <p class="text-gray-600 mb-4"><strong>สถานที่:</strong> {{ $repair->location }} | <strong>ผู้แจ้ง:</strong>
                {{ $repair->requester->name }}
            </p>
            <p class="mb-4"><strong>รายละเอียด:</strong> {{ $repair->description }}</p>

            @if($repair->image_path)
                <div class="mb-4">
                    <img src="{{ asset('storage/' . $repair->image_path) }}" class="max-w-md rounded border">
                </div>
            @endif
        </div>

        @if(Auth::user()->role !== 'user')
            <form action="{{ route('repairs.updateStatus', $repair->id) }}" method="POST"
                class="bg-white p-6 shadow-sm rounded-lg space-y-4">
                @csrf
                @method('PATCH')
                <h4 class="font-bold text-lg">อัปเดตสถานะงาน (สำหรับช่าง/ผู้ดูแล)</h4>
                <div>
                    <label class="block mb-1">สถานะ</label>
                    <select name="status" class="w-full border rounded p-2">
                        <option value="pending" {{ $repair->status == 'pending' ? 'selected' : '' }}>รอดำเนินการ</option>
                        <option value="in_progress" {{ $repair->status == 'in_progress' ? 'selected' : '' }}>กำลังดำเนินการ
                        </option>
                        <option value="completed" {{ $repair->status == 'completed' ? 'selected' : '' }}>เสร็จสิ้น</option>
                        <option value="cancelled" {{ $repair->status == 'cancelled' ? 'selected' : '' }}>ยกเลิก</option>
                    </select>
                </div>
                <div>
                    <label class="block mb-1">บันทึกการซ่อม/หมายเหตุ</label>
                    <textarea name="resolution_note"
                        class="w-full border rounded p-2">{{ $repair->resolution_note }}</textarea>
                </div>
                <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">บันทึกการอัปเดต</button>
            </form>
        @endif
    </div>
</x-app-layout>