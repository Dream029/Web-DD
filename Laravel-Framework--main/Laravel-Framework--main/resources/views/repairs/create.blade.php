<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">แบบฟอร์มแจ้งซ่อม</h2>
    </x-slot>

    <div class="py-12 max-w-2xl mx-auto sm:px-6 lg:px-8">
        <form action="{{ route('repairs.store') }}" method="POST" enctype="multipart/form-data"
            class="bg-white p-6 shadow-sm rounded-lg space-y-4">
            @csrf
            <div>
                <label class="block font-bold mb-1">หัวข้อปัญหา</label>
                <input type="text" name="title" class="w-full border rounded p-2" required>
            </div>
            <div>
                <label class="block font-bold mb-1">สถานที่ / ห้อง</label>
                <input type="text" name="location" class="w-full border rounded p-2" required>
            </div>
            <div>
                <label class="block font-bold mb-1">ระดับความเร่งด่วน</label>
                <select name="priority" class="w-full border rounded p-2">
                    <option value="low">ต่ำ</option>
                    <option value="medium" selected>ปานกลาง</option>
                    <option value="high">สูง</option>
                    <option value="urgent">ฉุกเฉิน</option>
                </select>
            </div>
            <div>
                <label class="block font-bold mb-1">รายละเอียดปัญหา</label>
                <textarea name="description" rows="4" class="w-full border rounded p-2" required></textarea>
            </div>
            <div>
                <label class="block font-bold mb-1">แนบรูปภาพประกอบ</label>
                <input type="file" name="image" class="w-full">
            </div>
            <button type="submit"
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">ส่งข้อมูลแจ้งซ่อม</button>
        </form>
    </div>
</x-app-layout>