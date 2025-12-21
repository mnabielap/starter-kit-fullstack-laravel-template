import sys
import os
import random
import string

sys.path.append(os.path.abspath(os.path.dirname(__file__)))
import utils

# Generate random user
random_str = ''.join(random.choices(string.ascii_lowercase + string.digits, k=5))
email = f"newuser_{random_str}@test.com"

url = f"{utils.BASE_URL}/users"
output_file = f"{os.path.splitext(os.path.basename(__file__))[0]}.json"

# Load Access Token (Admin)
token = utils.load_config("access_token")
headers = {"Authorization": f"Bearer {token}"} if token else {}

payload = {
    "name": f"Test User {random_str}",
    "email": email,
    "password": "Password123",
    "role": "user"
}

response = utils.send_and_print(
    url,
    headers=headers,
    method="POST",
    body=payload,
    output_file=output_file
)

# Save User ID for other tests
if response.status_code == 201:
    data = response.json()
    user_id = data.get("id")
    if user_id:
        utils.save_config("test_user_id", user_id)
        print(f"[SUCCESS] Created User ID {user_id} saved to config.")