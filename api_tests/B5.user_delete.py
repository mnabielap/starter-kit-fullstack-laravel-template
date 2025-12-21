import sys
import os

sys.path.append(os.path.abspath(os.path.dirname(__file__)))
import utils

# Load User ID created in B1
user_id = utils.load_config("test_user_id")
if not user_id:
    print("[ERROR] No test_user_id found. Run B1.user_create.py first.")
    sys.exit(1)

url = f"{utils.BASE_URL}/users/{user_id}"
output_file = f"{os.path.splitext(os.path.basename(__file__))[0]}.json"

# Load Access Token
token = utils.load_config("access_token")
headers = {"Authorization": f"Bearer {token}"} if token else {}

response = utils.send_and_print(
    url,
    headers=headers,
    method="DELETE",
    output_file=output_file
)

if response.status_code == 204:
    print(f"[SUCCESS] User ID {user_id} deleted.")
    # Clear config so B3/B4 don't try to access deleted user next time
    utils.save_config("test_user_id", None)