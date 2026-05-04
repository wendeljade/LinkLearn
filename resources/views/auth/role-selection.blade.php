<div class="container" style="text-align: center; padding: 50px;">
    <h1>Welcome to LinkLearn!</h1>
    <p>How would you like to use the platform?</p>

    <div style="display: flex; justify-content: center; gap: 30px; margin-top: 30px;">
        <div style="border: 1px solid #ccc; padding: 20px; border-radius: 10px; width: 300px;">
            <h3>I am an Educator</h3>
            <p>I want to create an organization, manage tutors, and host classes.</p>
            <a href="{{ route('org.register') }}" style="background: #4F46E5; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">
                Create Organization
            </a>
        </div>

        <div style="border: 1px solid #ccc; padding: 20px; border-radius: 10px; width: 300px;">
            <h3>I am a Student</h3>
            <p>I want to join an existing class and learn from experts.</p>
            <a href="/find-class" style="background: #10B981; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">
                Find a Class
            </a>
        </div>
    </div>
</div>