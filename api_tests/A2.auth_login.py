import sys
import os

sys.path.append(os.path.abspath(os.path.dirname(__file__)))
import utils

url = f"{utils.BASE_URL}/auth/login"
output_file = f"{os.path.splitext(os.path.basename(__file__))[0]}.json"

# Credentials (from DatabaseSeeder / Tinker)
payload = {
    "email": "admin@example.com",
    "password": "password123"
}

response = utils.send_and_print(
    url,
    method="POST",
    body=payload,
    output_file=output_file
)

# Save tokens if successful
if response.status_code == 200:
    data = response.json()
    if 'tokens' in data:
        utils.save_config("access_token", data['tokens']['access']['token'])
        utils.save_config("refresh_token", data['tokens']['refresh']['token'])
        print("[SUCCESS] Tokens saved to secrets.json")