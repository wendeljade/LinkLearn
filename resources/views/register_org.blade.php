<div class="max-w-md mx-auto mt-10 p-6 bg-white shadow-lg rounded-lg">
    <h2 class="text-2xl font-bold mb-4">Setup Your Organization</h2>
    <p class="text-gray-600 mb-6">Welcome, {{ auth()->user()->name }}. Please provide the name of your tutoring center to get started.</p>

    <form action="{{ route('org.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Organization Name</label>
            <input type="text" name="name" class="mt-1 block w-full border border-gray-300 rounded-md p-2" placeholder="e.g. Elite Math Tutorials" required>
        </div>
        
        <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 transition">
            Create Organization & Launch Dashboard
        </button>
    </form>
</div>