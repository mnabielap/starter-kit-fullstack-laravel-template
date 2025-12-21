import sys
import os
import random
import string

sys.path.append(os.path.abspath(os.path.dirname(__file__)))
import utils

# Generate random email to avoid unique constraint error
random_str = ''.join(random.choices(string.ascii_lowercase + string.digits, k=6))
email = f"user_{random_str}@example.com"
password = "Password123"

url = f"{utils.BASE_URL}/auth/register"
output_file = f"{os.path.splitext(os.path.basename(__file__))[0]}.json"

payload = {
    "name": f"User {random_str}",
    "email": email,
    "password": password
}

print(f"Registering with: {email} / {password}")

response = utils.send_and_print(
    url,
    method="POST",
    body=payload,
    output_file=output_file
)

# Save tokens if successful
if response.status_code == 201:
    data = response.json()
    if 'tokens' in data:
        utils.save_config("access_token", data['tokens']['access']['token'])
        utils.save_config("refresh_token", data['tokens']['refresh']['token'])
        print("[SUCCESS] Tokens saved to secrets.json")