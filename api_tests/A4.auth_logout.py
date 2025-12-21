import sys
import os

sys.path.append(os.path.abspath(os.path.dirname(__file__)))
import utils

url = f"{utils.BASE_URL}/auth/logout"
output_file = f"{os.path.splitext(os.path.basename(__file__))[0]}.json"

# Load Access Token
token = utils.load_config("access_token")
headers = {"Authorization": f"Bearer {token}"} if token else {}

response = utils.send_and_print(
    url,
    headers=headers,
    method="POST",
    output_file=output_file
)

if response.status_code == 204:
    print("[SUCCESS] User logged out.")