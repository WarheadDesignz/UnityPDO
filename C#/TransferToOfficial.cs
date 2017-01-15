using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;

public class TransferToOfficial : MonoBehaviour {


	public InputField userName;
	public InputField email;
	public InputField password;
	public InputField confirmPassword;

	private string _userName;
	private string _email;
	private string _password;
	private string _confirmPassword;

	private string form;
	private bool emailValid = false;
	private string uri = "URL to - transferaccountreal.php";

	private List<string> randomSpecialKeys = new List<string>();

	public void Submit(){
		_userName = userName.text;
		_email = email.text;
		_password = password.text;
		_confirmPassword = confirmPassword.text;

		if (_userName == "" || _email == "" || _password == "" || _confirmPassword == "") {
			Debug.LogError ("One or more of the Input Fields are empty!");
			return;
		}

		if (_confirmPassword != _password) {
			Debug.LogError ("Confirm password isn't the same as Password!");
			return;
		}

		if (_userName != "" && _email != "" && _password == _confirmPassword && _confirmPassword == _password) {
			Debug.Log ("Attempting to register account, please standby...");
			//Register ();
			StartCoroutine(Register());
		}

	}


	IEnumerator Register(){
		// Now we attempt to register the account.
		WWWForm form = new WWWForm();
		//Get your Guest Act
		form.AddField("guestUsernamePost",ActExist.username);
		form.AddField("guestPassPost",ActExist.password);

		// Transfer Details.
		form.AddField ("usernamePost",_userName);
		form.AddField ("emailPost",_email);
		form.AddField("passwordPost",_password);

		WWW www = new WWW (uri,form);

		yield return www;
		Debug.Log (www.text);
		if (www.text.Length > 1) {
			Debug.Log ("Account Already Exists - Attempting again!");
		} else if (www.text.Length == 1) {
			Debug.Log ("You Are Now Registered!");
			// Now we save our login details on our device so we don't have to type it in
			// Every Single Time.
			//	PlayerPrefs.SetString ("un", _userName);
			//	PlayerPrefs.SetString ("pw", _password);
		}
	}
}
